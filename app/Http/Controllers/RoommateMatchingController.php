<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommateMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoommateMatchingController extends Controller
{
    /**
     * Get compatible roommates for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompatibleRoommates(Request $request)
    {
        $request->validate([
            'min_score' => 'sometimes|integer|min:0|max:100',
            'limit' => 'sometimes|integer|min:1|max:50',
        ]);

        $user = Auth::user();
        $minScore = $request->input('min_score', 50);
        $limit = $request->input('limit', 10);

        $compatibleRoommates = $user->getCompatibleRoommates($minScore, $limit);

        return response()->json([
            'success' => true,
            'data' => $compatibleRoommates->values(),
            'meta' => [
                'total' => $compatibleRoommates->count(),
                'min_score' => $minScore,
            ]
        ]);
    }

    /**
     * Send a roommate match request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMatchRequest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $matchedUser = User::findOrFail($request->user_id);

        // Check if users are already matched
        $existingMatch = RoommateMatch::where(function ($query) use ($user, $matchedUser) {
            $query->where('user_id', $user->id)
                ->where('matched_user_id', $matchedUser->id);
        })->orWhere(function ($query) use ($user, $matchedUser) {
            $query->where('user_id', $matchedUser->id)
                ->where('matched_user_id', $user->id);
        })->first();

        if ($existingMatch) {
            return response()->json([
                'success' => false,
                'message' => 'Match request already exists between these users',
                'match' => $existingMatch
            ], 400);
        }

        // Calculate compatibility score
        $compatibility = $user->calculateCompatibilityScore($matchedUser);

        // Create match request
        $match = new RoommateMatch([
            'user_id' => $user->id,
            'matched_user_id' => $matchedUser->id,
            'compatibility_score' => $compatibility['score'],
            'matching_preferences' => $compatibility['details'],
            'message' => $request->message,
            'status' => 'pending',
            'last_interaction_at' => now()
        ]);

        $match->save();

        // Create notification for the matched user
        $matchedUser->notifications()->create([
            'type' => 'match_request',
            'message' => 'You have a new roommate match request from ' . $user->name,
            'data' => [
                'match_id' => $match->id,
                'from_user_id' => $user->id,
                'from_user_name' => $user->name,
                'compatibility_score' => $compatibility['score']
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Match request sent successfully',
            'match' => $match->load('matchedUser')
        ], 201);
    }

    /**
     * Respond to a match request
     *
     * @param Request $request
     * @param int $matchId
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondToMatch(Request $request, $matchId)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'message' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $match = RoommateMatch::where('id', $matchId)
            ->where('matched_user_id', $user->id)
            ->firstOrFail();

        if ($match->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This match request has already been processed'
            ], 400);
        }

        $match->update([
            'status' => $request->status,
            'last_interaction_at' => now()
        ]);

        // Create notification for the other user
        $otherUser = $match->user;
        $statusMessage = $request->status === 'accepted' ? 'accepted' : 'declined';
        
        // If the match is accepted, create a special notification with a link to start a conversation
        if ($request->status === 'accepted') {
            $otherUser->notify(new \App\Notifications\RoommateRequestAccepted($match));
        }
        
        // Also create a regular notification for the response
        $otherUser->notifications()->create([
            'type' => 'match_response',
            'message' => $user->name . ' has ' . $statusMessage . ' your roommate request',
            'data' => [
                'match_id' => $match->id,
                'status' => $request->status,
                'from_user_id' => $user->id,
                'from_user_name' => $user->name
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Match request ' . $statusMessage . ' successfully',
            'match' => $match->load('user')
        ]);
    }

    /**
     * Get all matches for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatches(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status', 'accepted');
        
        $matches = RoommateMatch::with(['user', 'matchedUser'])
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('matched_user_id', $user->id);
            })
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('last_interaction_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $matches
        ]);
    }

    /**
     * Get match details
     *
     * @param int $matchId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatch($matchId)
    {
        $user = Auth::user();
        
        $match = RoommateMatch::with(['user', 'matchedUser'])
            ->where('id', $matchId)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('matched_user_id', $user->id);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $match
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
