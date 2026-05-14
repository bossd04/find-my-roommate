<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CallController extends Controller
{
    /**
     * Initiate a new call
     */
    public function initiate(User $user, Request $request)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot call yourself'], 400);
        }

        $validated = $request->validate([
            'call_type' => 'required|string|in:audio,video',
            'offer_sdp' => 'nullable|string',
        ]);

        // Check for existing active call between these two users
        $existingCall = Call::where(function($query) use ($currentUser, $user) {
                $query->where(function($q) use ($currentUser, $user) {
                    $q->where('caller_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($currentUser, $user) {
                    $q->where('caller_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
                });
            })
            ->active()
            ->first();

        if ($existingCall) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is already an active call between you and this user',
                'call_id' => $existingCall->id
            ], 400);
        }

        $call = Call::create([
            'caller_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'call_type' => $validated['call_type'],
            'status' => 'pending',
            'offer_sdp' => $validated['offer_sdp'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'call_id' => $call->id,
            'message' => 'Call initiated successfully'
        ]);
    }

    /**
     * Accept an incoming call
     */
    public function accept(Call $call, Request $request)
    {
        $currentUser = Auth::user();

        if ($call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if (!$call->isPending()) {
            return response()->json(['status' => 'error', 'message' => 'Call is no longer pending'], 400);
        }

        $validated = $request->validate([
            'answer_sdp' => 'nullable|string',
        ]);

        $call->markAsConnected();

        if (isset($validated['answer_sdp'])) {
            $call->update(['answer_sdp' => $validated['answer_sdp']]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Call accepted'
        ]);
    }

    /**
     * Decline an incoming call
     */
    public function decline(Call $call)
    {
        $currentUser = Auth::user();

        if ($call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Mark as missed instead of declined for consistent tracking
        $call->markAsMissed();

        // Create missed call message
        $this->createMissedCallMessage($call);

        return response()->json([
            'status' => 'success',
            'message' => 'Call declined'
        ]);
    }

    /**
     * Create a missed call message in the conversation
     */
    private function createMissedCallMessage(Call $call)
    {
        try {
            DB::beginTransaction();

            \Log::info('Creating missed call message for call: ' . $call->id);

            // Get or create conversation
            $conversation = Conversation::firstOrCreate(
                [
                    'user1_id' => min($call->caller_id, $call->receiver_id),
                    'user2_id' => max($call->caller_id, $call->receiver_id),
                ],
                ['last_message_at' => now()]
            );

            \Log::info('Conversation ID: ' . $conversation->id);

            // Create the missed call message
            $messageText = $call->call_type === 'video'
                ? '📹 Missed video call'
                : '📞 Missed voice call';

            \Log::info('Creating message: ' . $messageText);

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $call->caller_id,
                'receiver_id' => $call->receiver_id,
                'content' => $messageText,
                'message_type' => 'system',
                'delivery_status' => 'delivered',
                'is_delivered' => true,
                'is_read' => false,
            ]);

            \Log::info('Message created with ID: ' . $message->id);

            // Update conversation
            $conversation->update(['last_message_at' => $message->created_at]);

            // Increment unread count for receiver
            if ($conversation->user1_id == $call->receiver_id) {
                $conversation->increment('unread_count_user1');
            } else {
                $conversation->increment('unread_count_user2');
            }

            DB::commit();
            \Log::info('Missed call message created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create missed call message: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }

    /**
     * End an active call
     */
    public function end(Call $call)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id && $call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($call->hasEnded()) {
            return response()->json(['status' => 'error', 'message' => 'Call already ended'], 400);
        }

        // If call was pending and ended by caller (timeout or hangup), mark as missed
        if ($call->wasPending() && $call->caller_id === $currentUser->id) {
            $call->markAsMissed();
            $this->createMissedCallMessage($call);
        } else {
            $call->markAsEnded();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Call ended',
            'duration' => $call->getFormattedDuration()
        ]);
    }

    /**
     * Get call details
     */
    public function show(Call $call)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id && $call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'call' => $call->load(['caller', 'receiver'])
        ]);
    }

    /**
     * Check for incoming calls
     */
    public function checkIncoming()
    {
        $currentUser = Auth::user();

        // Clean up stale calls older than 2 minutes
        $this->cleanupStaleCalls($currentUser->id);

        $incomingCall = Call::where('receiver_id', $currentUser->id)
            ->where('status', 'pending')
            ->with('caller')
            ->latest()
            ->first();

        return response()->json([
            'has_incoming' => (bool) $incomingCall,
            'call' => $incomingCall
        ]);
    }

    /**
     * Clean up stale calls (older than 2 minutes)
     */
    private function cleanupStaleCalls($userId)
    {
        $staleCalls = Call::where(function($query) use ($userId) {
                $query->where('caller_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->whereIn('status', ['pending', 'connected'])
            ->where('created_at', '<', now()->subMinutes(2))
            ->get();

        foreach ($staleCalls as $call) {
            $call->markAsEnded();
        }
    }

    /**
     * Force end all active calls for current user
     */
    public function forceEndAll()
    {
        $currentUser = Auth::user();

        $activeCalls = Call::where(function($query) use ($currentUser) {
                $query->where('caller_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->whereIn('status', ['pending', 'connected'])
            ->get();

        foreach ($activeCalls as $call) {
            $call->markAsEnded();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'All active calls ended',
            'count' => $activeCalls->count()
        ]);
    }

    /**
     * Send ICE candidate
     */
    public function sendIceCandidate(Call $call, Request $request)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id && $call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'candidate' => 'required|string',
        ]);

        $call->update(['ice_candidate' => $validated['candidate']]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Get ICE candidate
     */
    public function getIceCandidate(Call $call)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id && $call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'candidate' => $call->ice_candidate
        ]);
    }

    /**
     * Update call offer SDP
     */
    public function updateOffer(Call $call, Request $request)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'offer_sdp' => 'required|string',
        ]);

        $call->update(['offer_sdp' => $validated['offer_sdp']]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Get call status for polling
     */
    public function status(Call $call)
    {
        $currentUser = Auth::user();

        if ($call->caller_id !== $currentUser->id && $call->receiver_id !== $currentUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'call_status' => $call->status,
            'answer_sdp' => $call->answer_sdp,
            'ice_candidate' => $call->ice_candidate,
        ]);
    }
}
