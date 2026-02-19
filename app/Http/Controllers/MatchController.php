<?php

namespace App\Http\Controllers;

use App\Models\RoommateMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'all');
        
        // Initialize empty collections
        $matches = collect();
        $potentialMatches = collect();
            
        // Only try to query matches if the table exists
        if (Schema::hasTable('roommate_matches')) {
            // Get all user IDs that the current user has interacted with
            $interactedUserIds = DB::table('roommate_matches')
                ->where('user_id', $user->id)
                ->orWhere('matched_user_id', $user->id)
                ->get()
                ->flatMap(function ($match) use ($user) {
                    return [$match->user_id, $match->matched_user_id];
                })
                ->unique()
                ->filter(function ($id) use ($user) {
                    return $id != $user->id;
                });
            
            // Get potential matches (users not interacted with yet)
            if ($filter === 'all') {
                $potentialMatches = User::whereNotIn('id', $interactedUserIds)
                    ->where('id', '!=', $user->id)
                    ->with(['profile', 'preferences'])
                    ->inRandomOrder()
                    ->limit(12)
                    ->get();
            }
            
            // Get existing matches for the current user
            $matchesQuery = RoommateMatch::where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('matched_user_id', $user->id);
                })
                ->with(['user.profile', 'matchedUser.profile']);
            
            // Apply filters
            if ($filter === 'pending') {
                $matchesQuery->where('status', 'pending')
                    ->where(function($q) use ($user) {
                        $q->where(function($q) use ($user) {
                            $q->where('user_id', $user->id)
                              ->where('user_action', 'liked');
                        })->orWhere(function($q) use ($user) {
                            $q->where('matched_user_id', $user->id)
                              ->where('user_action', 'liked');
                        });
                    });
            } elseif ($filter === 'accepted') {
                $matchesQuery->where('status', 'accepted');
            } elseif ($filter === 'new') {
                $matchesQuery->where('created_at', '>=', now()->subDays(7));
            }
            
            $matches = $matchesQuery->latest()->get()
                ->map(function($match) use ($user) {
                    // Always set the other user as the matched user for consistent display
                    $matchedUser = $match->user_id === $user->id ? $match->matchedUser : $match->user;
                    $match->display_user = $matchedUser;
                    return $match;
                });
                
            // Remove duplicates by user ID
            $uniqueMatches = collect();
            $seenUserIds = [];
            
            foreach ($matches as $match) {
                if (!in_array($match->display_user->id, $seenUserIds)) {
                    $uniqueMatches->push($match);
                    $seenUserIds[] = $match->display_user->id;
                }
            }
            
            $matches = new \Illuminate\Pagination\LengthAwarePaginator(
                $uniqueMatches->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 12),
                $uniqueMatches->count(),
                12,
                \Illuminate\Pagination\Paginator::resolveCurrentPage(),
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        }
        
        return view('matches.index', [
            'matches' => $matches,
            'potentialMatches' => $potentialMatches,
            'filter' => $filter
        ]);
    }
    
    /**
     * Get potential matches for the user
     */
    protected function getPotentialMatches($user)
    {
        // Get users who haven't been matched with yet
        $excludedUserIds = $user->allMatches()->pluck('matched_user_id');
        $excludedUserIds[] = $user->id; // Exclude self
        
        return User::whereNotIn('id', $excludedUserIds)
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(10)
            ->get();
    }

    /**
     * Store a new match action (like/dislike)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|in:like,dislike'
        ]);
        
        $user = Auth::user();
        $matchedUserId = $request->user_id;
        $action = $request->action;
        
        // Check if this user has already been matched with
        $existingMatch = RoommateMatch::where('user_id', $user->id)
            ->where('matched_user_id', $matchedUserId)
            ->first();
            
        if ($existingMatch) {
            return response()->json([
                'success' => false,
                'message' => 'You have already taken an action on this user.'
            ]);
        }
        
        // Create new match record
        $match = new RoommateMatch([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUserId,
            'user_action' => $action === 'like' ? 'liked' : 'disliked',
            'status' => 'pending'
        ]);
        
        // Check if the other user has already liked this user
        $mutualMatch = RoommateMatch::where('user_id', $matchedUserId)
            ->where('matched_user_id', $user->id)
            ->where('user_action', 'liked')
            ->first();
            
        if ($mutualMatch) {
            $match->status = 'accepted';
            $mutualMatch->update(['status' => 'accepted']);
            $match->is_mutual = true;
            $mutualMatch->is_mutual = true;
            $mutualMatch->save();
            
            // Here you could trigger a notification or event
        }
        
        $match->save();
        
        return response()->json([
            'success' => true,
            'is_match' => $match->status === 'accepted',
            'match_id' => $match->id
        ]);
    }

    /**
     * Update the match status (accept/reject)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);
        
        $match = RoommateMatch::findOrFail($id);
        $user = Auth::user();
        
        // Ensure the user is authorized to update this match
        if ($match->matched_user_id !== $user->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }
        
        $match->status = $request->status;
        $match->save();
        
        // If accepted, check if this creates a mutual match
        if ($request->status === 'accepted') {
            $mutualMatch = RoommateMatch::where('user_id', $user->id)
                ->where('matched_user_id', $match->user_id)
                ->first();
                
            if ($mutualMatch) {
                $mutualMatch->update([
                    'status' => 'accepted',
                    'is_mutual' => true
                ]);
                $match->is_mutual = true;
                $match->save();
                
                // Create a welcome message in the conversation
                $welcomeMessage = new \App\Models\Message([
                    'sender_id' => $user->id,
                    'receiver_id' => $match->user_id,
                    'message' => "Hi there! I've accepted your match request. Let's chat!"
                ]);
                $welcomeMessage->save();
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'status' => $match->status,
                        'is_mutual' => $match->is_mutual,
                        'redirect_url' => route('messages.show', $match->user_id)
                    ]);
                }
                
                return redirect()->route('messages.show', $match->user_id);
            }
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $match->status,
                'is_mutual' => $match->is_mutual
            ]);
        }
        
        return back()->with('status', 'Match updated successfully');
    }
}
