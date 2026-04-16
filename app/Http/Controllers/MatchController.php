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
        
        if (!Schema::hasTable('roommate_matches')) {
            return view('matches.index', [
                'matches' => collect(),
                'potentialMatches' => collect(),
                'filter' => $filter
            ]);
        }
        
        $interactedUserIds = $this->getInteractedUserIds($user);
        $potentialMatches = $this->getFilteredPotentialMatches($user, $interactedUserIds, $filter);
        $matches = $this->getFilteredMatches($user, $filter);
        $matches = $this->deduplicateAndPaginateMatches($matches);
        
        return view('matches.index', [
            'matches' => $matches,
            'potentialMatches' => $potentialMatches,
            'filter' => $filter
        ]);
    }
    
    /**
     * Get user IDs that the current user has interacted with (only disliked users)
     * Liked users should remain visible in potential matches
     */
    private function getInteractedUserIds($user): \Illuminate\Support\Collection
    {
        return DB::table('roommate_matches')
            ->where('user_id', $user->id)
            ->where('user_action', 'disliked')
            ->get()
            ->flatMap(fn($match) => [$match->matched_user_id])
            ->unique()
            ->filter(fn($id) => $id != $user->id);
    }
    
    /**
     * Get potential matches with location filter
     * Include liked users so they remain visible after Add action
     */
    private function getFilteredPotentialMatches($user, $interactedUserIds, string $filter): \Illuminate\Support\Collection
    {
        if ($filter !== 'all') {
            return collect();
        }
        
        // Get all users except disliked ones and self
        $potentialMatches = User::whereNotIn('id', $interactedUserIds)
            ->where('id', '!=', $user->id)
            ->where(function($query) {
                $query->whereNotNull('location')->where('location', '!=', '')
                    ->orWhereHas('profile', function($q) {
                        $q->whereNotNull('city')->where('city', '!=', '')
                          ->orWhereNotNull('apartment_location')->where('apartment_location', '!=', '');
                    });
            })
            ->with(['profile', 'preferences', 'preference'])
            ->inRandomOrder()
            ->limit(12)
            ->get();
        
        // Calculate compatibility scores for each potential match based on preferences
        return $potentialMatches->map(function($potentialMatch) use ($user) {
            $score = $user->calculatePreferenceMatchingScore($potentialMatch);
            $details = $user->getDetailedMatchingPreferences($potentialMatch);
            $potentialMatch->compatibility_score = $score;
            $potentialMatch->compatibility_details = $details;
            $potentialMatch->interaction_data = [];
            
            // Check if user has already liked this person
            $existingLike = \App\Models\RoommateMatch::where('user_id', $user->id)
                ->where('matched_user_id', $potentialMatch->id)
                ->where('user_action', 'liked')
                ->first();
            
            if ($existingLike) {
                $potentialMatch->already_liked = true;
                $potentialMatch->match_status = $existingLike->status;
                $potentialMatch->match_id = $existingLike->id;
            }
            
            return $potentialMatch;
        })->sortByDesc('compatibility_score');
    }
    
    /**
     * Get matches with filter applied
     */
    private function getFilteredMatches($user, string $filter): \Illuminate\Support\Collection
    {
        $query = RoommateMatch::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('matched_user_id', $user->id);
            })
            ->with(['user.profile', 'user.preference', 'matchedUser.profile', 'matchedUser.preference']);
        
        $this->applyMatchFilter($query, $filter, $user);
        
        return $query->latest()->get()
            ->map(function($match) use ($user) {
                $match->display_user = $match->user_id === $user->id ? $match->matchedUser : $match->user;
                
                // Calculate compatibility for the match based on preferences
                if ($match->display_user) {
                    $score = $user->calculatePreferenceMatchingScore($match->display_user);
                    $match->display_user->compatibility_score = $score;
                    $match->display_user->interaction_data = [];
                }
                
                return $match;
            });
    }
    
    /**
     * Apply filter to matches query
     */
    private function applyMatchFilter($query, string $filter, $user): void
    {
        match ($filter) {
            'pending' => $query->where('status', 'pending')
                ->where(function($q) use ($user) {
                    $q->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)->where('user_action', 'liked');
                    })->orWhere(function($q) use ($user) {
                        $q->where('matched_user_id', $user->id)->where('user_action', 'liked');
                    });
                }),
            'accepted' => $query->where('status', 'accepted'),
            'new' => $query->where('created_at', '>=', now()->subDays(7)),
            default => null
        };
    }
    
    /**
     * Remove duplicate matches and paginate
     */
    private function deduplicateAndPaginateMatches(\Illuminate\Support\Collection $matches): \Illuminate\Pagination\LengthAwarePaginator
    {
        $seenUserIds = [];
        $uniqueMatches = $matches->filter(function($match) use (&$seenUserIds) {
            if (in_array($match->display_user->id, $seenUserIds)) {
                return false;
            }
            $seenUserIds[] = $match->display_user->id;
            return true;
        })->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $uniqueMatches->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 12),
            $uniqueMatches->count(),
            12,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
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
        
        // Track compatibility interaction for likes
        if ($action === 'like') {
            $matchedUser = User::find($matchedUserId);
            $compatibility = $user->getCompatibilityWith($matchedUser);
            $compatibility->addInteraction('like');
        }
        
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
        
        // Get updated compatibility score
        $compatibilityScore = 0;
        if ($action === 'like') {
            $matchedUser = User::find($matchedUserId);
            $compatibilityResult = $user->calculateCompatibilityScore($matchedUser);
            $compatibilityScore = $compatibilityResult['score'];
        }
        
        return response()->json([
            'success' => true,
            'is_match' => $match->status === 'accepted',
            'match_id' => $match->id,
            'compatibility_score' => $compatibilityScore,
            'matched_user_id' => $matchedUserId
        ]);
    }

    /**
     * Like a user
     */
    public function like(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if already interacted with this user
        $existingMatch = RoommateMatch::where('user_id', $currentUser->id)
            ->where('matched_user_id', $user->id)
            ->first();
            
        if (!$existingMatch) {
            // Check if the other user has already liked this user
            $mutualMatch = RoommateMatch::where('user_id', $user->id)
                ->where('matched_user_id', $currentUser->id)
                ->where('user_action', 'liked')
                ->first();
            
            $match = new RoommateMatch([
                'user_id' => $currentUser->id,
                'matched_user_id' => $user->id,
                'user_action' => 'liked',
                'status' => $mutualMatch ? 'accepted' : 'pending',
                'is_mutual' => (bool)$mutualMatch
            ]);
            
            // Track compatibility interaction
            $compatibility = $currentUser->getCompatibilityWith($user);
            $compatibility->addInteraction('like');
            
            if ($mutualMatch) {
                $mutualMatch->update([
                    'status' => 'accepted',
                    'is_mutual' => true
                ]);
                $match->is_mutual = true;
            }
            
            $match->save();
            
            // Get updated compatibility score
            $compatibilityResult = $currentUser->calculateCompatibilityScore($user);
            $compatibilityScore = $compatibilityResult['score'];
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_match' => $match->status === 'accepted',
                    'match_id' => $match->id,
                    'compatibility_score' => $compatibilityScore,
                    'matched_user_id' => $user->id
                ]);
            }
            
            return back()->with('status', $mutualMatch ? 'It\'s a match!' : 'Liked!');
        } else if ($existingMatch->user_action !== 'liked') {
            $existingMatch->update([
                'user_action' => 'liked',
                'status' => 'pending',
                'updated_at' => now()
            ]);
            
            // Track compatibility interaction
            $compatibility = $currentUser->getCompatibilityWith($user);
            $compatibility->addInteraction('like');
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            
            return back()->with('status', 'Liked!');
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Already liked this user']);
        }
        
        return back()->with('status', 'Already liked this user');
    }
    
    /**
     * Dislike a user
     */
    public function dislike(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $existingMatch = RoommateMatch::where('user_id', $currentUser->id)
            ->where('matched_user_id', $user->id)
            ->first();
            
        if (!$existingMatch) {
            RoommateMatch::create([
                'user_id' => $currentUser->id,
                'matched_user_id' => $user->id,
                'user_action' => 'disliked',
                'status' => 'rejected'
            ]);
        } else if ($existingMatch->user_action !== 'disliked') {
            $existingMatch->update([
                'user_action' => 'disliked',
                'status' => 'rejected',
                'updated_at' => now()
            ]);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('status', 'Disliked');
    }
    
    /**
     * Accept a match
     */
    public function accept(Request $request, RoommateMatch $match)
    {
        $currentUser = Auth::user();
        
        // Ensure the current user is the recipient of the match
        if ($match->matched_user_id !== $currentUser->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update the match status to accepted
        $match->update([
            'status' => 'accepted',
            'user_action' => 'liked',
            'is_mutual' => true,
            'updated_at' => now()
        ]);
        
        // Create a notification for the other user
        $match->user->notify(new \App\Notifications\MatchAccepted($match->matchedUser));
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => 'accepted',
                'redirect_url' => route('messages.show', $match->user_id)
            ]);
        }
        
        return back()->with('status', 'Match accepted!');
    }
    
    /**
     * Reject a match
     */
    public function reject(Request $request, RoommateMatch $match)
    {
        $currentUser = Auth::user();
        
        // Ensure the current user is the recipient of the match
        if ($match->matched_user_id !== $currentUser->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $match->update([
            'status' => 'rejected',
            'user_action' => 'rejected',
            'updated_at' => now()
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => 'rejected'
            ]);
        }
        
        return back()->with('status', 'Match rejected');
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
