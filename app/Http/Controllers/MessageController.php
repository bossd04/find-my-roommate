<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MutedConversation;
use App\Models\User;
use App\Models\UserBlock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index(User $user = null)
    {
        $currentUser = Auth::user();

        // Get all conversations for the current user
        $conversations = $this->getConversations($currentUser);

        // If there are existing conversations and no specific user is selected,
        // redirect to the most recent conversation automatically
        if ($conversations->isNotEmpty() && !$user) {
            $mostRecent = $conversations->sortByDesc(function ($c) {
                return $c->last_message?->created_at;
            })->first();

            if ($mostRecent && $mostRecent->user) {
                return redirect()->route('messages.show', $mostRecent->user->id);
            }
        }

        // Get conversation with a specific user (if provided)
        $selectedUser = null;
        $messages = collect();

        if ($user) {
            $selectedUser = $user;

            // Mark messages as read
            Message::where('sender_id', $user->id)
                ->where('receiver_id', $currentUser->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            // Get messages between users
            $messages = Message::where(function ($query) use ($currentUser, $user) {
                    $query->where('sender_id', $currentUser->id)
                          ->where('receiver_id', $user->id);
                })
                ->orWhere(function ($query) use ($currentUser, $user) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $currentUser->id);
                })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Build a set of muted user IDs so the sidebar can show mute indicators
        $mutedUserIds = MutedConversation::where('user_id', $currentUser->id)
            ->active()
            ->with('conversation')
            ->get()
            ->map(function ($mute) use ($currentUser) {
                $conv = $mute->conversation;
                if (!$conv) return null;
                return $conv->user1_id == $currentUser->id
                    ? $conv->user2_id
                    : $conv->user1_id;
            })
            ->filter()
            ->values()
            ->toArray();

        return view('messages.chat', [
            'conversations' => $conversations,
            'selectedUser'  => $selectedUser,
            'messages'      => $messages,
            'mutedUserIds'  => $mutedUserIds,
        ]);
    }
    
    
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id === Auth::id() && !$message->read_at) {
            $message->update(['read_at' => now()]);
            // Note: Broadcasting disabled to fix helper errors
            // broadcast(new MessageRead(Auth::user(), $message->sender));
        }
        
        return response()->json(['status' => 'Message marked as read']);
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Allow users to message admins, but block admin-to-admin messaging
        if ($user->is_admin && $currentUser->is_admin) {
            abort(403, 'Admin-to-admin messaging is not allowed.');
        }

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Get conversation between users
        $messages = Message::where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Get conversations list for sidebar with compatibility info
        $conversations = $this->getConversations($currentUser);
        
        // Calculate compatibility percentage (example: based on preferences)
        $compatibility = $this->calculateCompatibility($currentUser, $user);

        // Build a set of muted user IDs so the sidebar can show mute indicators
        $mutedUserIds = MutedConversation::where('user_id', $currentUser->id)
            ->active()
            ->with('conversation')
            ->get()
            ->map(function ($mute) use ($currentUser) {
                $conv = $mute->conversation;
                if (!$conv) return null;
                return $conv->user1_id == $currentUser->id
                    ? $conv->user2_id
                    : $conv->user1_id;
            })
            ->filter()
            ->values()
            ->toArray();

        return view('messages.show', [
            'messages'      => $messages,
            'conversations' => $conversations,
            'receiver'      => $user,
            'compatibility' => $compatibility,
            'currentUser'   => $currentUser,
            'mutedUserIds'  => $mutedUserIds,
        ]);
    }
    
    /**
     * Calculate compatibility percentage between two users
     */
    protected function calculateCompatibility($user1, $user2)
    {
        // Get user preferences
        $prefs1 = $user1->preferences;
        $prefs2 = $user2->preferences;
        
        if (!$prefs1 || !$prefs2) {
            return 0;
        }
        
        $score = 0;
        $total = 0;
        
        // Compare different preference fields
        $fields = ['smoking', 'drinking', 'cleanliness', 'schedule', 'sleep_schedule'];
        
        foreach ($fields as $field) {
            if (isset($prefs1->$field) && isset($prefs2->$field)) {
                $total++;
                if ($prefs1->$field == $prefs2->$field) {
                    $score++;
                }
            }
        }
        
        return $total > 0 ? round(($score / $total) * 100) : 0;
    }

    /**
     * Get or create a conversation between two users
     */
    protected function getOrCreateConversation($user1Id, $user2Id)
    {
        // Ensure user1_id is always the smaller ID to prevent duplicate conversations
        $user1Id = (int)$user1Id;
        $user2Id = (int)$user2Id;
        
        if ($user1Id > $user2Id) {
            [$user1Id, $user2Id] = [$user2Id, $user1Id];
        }
        
        return Conversation::firstOrCreate(
            ['user1_id' => $user1Id, 'user2_id' => $user2Id],
            ['last_message_at' => now()]
        );
    }

    public function store(User $user, Request $request)
    {
        $currentUser = Auth::user();

        // Check if there is a block between users
        // If current user is blocked by the recipient, prevent sending
        // If current user has blocked recipient, prevent sending (must unblock first)
        if ($currentUser->isBlockedBy($user->id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot reply to this conversation. You have been blocked.'
                ], 403);
            }
            return back()->with('error', 'You cannot reply to this conversation. You have been blocked.');
        }

        if ($currentUser->hasBlocked($user->id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot reply to this conversation. You have blocked this user. Unblock them to continue chatting.'
                ], 403);
            }
            return back()->with('error', 'You cannot reply to this conversation. You have blocked this user. Unblock them to continue chatting.');
        }

        // Allow users to message admins, but block admin-to-admin messaging
        if ($user->is_admin && $currentUser->is_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admin-to-admin messaging is not allowed.'
                ], 403);
            }
            return back()->with('error', 'Admin-to-admin messaging is not allowed.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            // Get or create conversation
            $conversation = $this->getOrCreateConversation($currentUser->id, $user->id);
            
            // Create the message
            $message = new Message([
                'sender_id' => $currentUser->id,
                'receiver_id' => $user->id,
                'content' => $validated['message'],
                'message_type' => 'text',
                'delivery_status' => 'delivered',
                'is_delivered' => true,
                'is_read' => false,
                'metadata' => null
            ]);
            
            // Associate with conversation
            $message->conversation()->associate($conversation);
            
            // Check for bad words/violations
            $content = strtolower($validated['message']);
            $badWords = [
                'fuck', 'shit', 'ass', 'bitch', 'idiot', 'stupid', 'bastard', 'crap', 'piss',
                'puta', 'tarantado', 'gago', 'tangina', 'bobo', 'salot', 'hayop', 'ulol', 'leche'
            ];
            
            $hasViolation = false;
            foreach ($badWords as $word) {
                if (preg_match("/\b{$word}\b/", $content)) {
                    $hasViolation = true;
                    break;
                }
            }
            
            // Update compatibility
            $compatibility = $currentUser->getCompatibilityWith($user);
            if ($hasViolation) {
                $compatibility->addInteraction('violation');
                // Store violation type in metadata
                $message->metadata = ['violation' => true, 'warning' => 'Violations of these guidelines may result in account suspension or permanent ban.'];
            } else {
                $compatibility->addInteraction('message');
            }
            
            $message->save();
            
            // Update conversation's last message timestamp
            $conversation->update(['last_message_at' => $message->created_at]);
            
            // Increment unread count for receiver
            if ($conversation->user1_id == $user->id) {
                $conversation->increment('unread_count_user1');
            } else {
                $conversation->increment('unread_count_user2');
            }
            
            // Load relationships for the frontend
            $message->load(['sender', 'receiver', 'conversation']);
            
            // Broadcast to other user
            broadcast(new MessageSent($message))->toOthers();
            
            DB::commit();
            
            // Check if this is an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => [
                        'id' => $message->id,
                        'content' => $message->content,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id,
                        'created_at' => $message->created_at->toISOString(),
                        'delivery_status' => $message->delivery_status ?? 'sent',
                        'sender' => [
                            'first_name' => $message->sender->first_name,
                            'avatar' => $message->sender->avatar
                        ]
                    ]
                ]);
            }
            
            // For regular form submissions, redirect back to the conversation
            return redirect()->route('messages.show', $user->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending message: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send message. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to send message. Please try again.');
        }
    }

    /**
     * Clear chat messages between two users
     */
    public function clearChat(User $user)
    {
        $currentUser = Auth::user();

        // Delete all messages between the two users
        Message::where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->delete();

        return response()->json(['status' => 'success', 'message' => 'Chat cleared successfully']);
    }

    /**
     * Search messages between two users
     */
    public function searchMessages(User $user, Request $request)
    {
        $currentUser = Auth::user();

        $validated = $request->validate([
            'query' => 'required|string|min:1|max:100'
        ]);

        $searchQuery = $validated['query'];

        // Search messages between the two users
        $messages = Message::where(function($query) use ($currentUser, $user, $searchQuery) {
                $query->where(function($q) use ($currentUser, $user) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
                })
                ->orWhere(function($q) use ($currentUser, $user) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
                });
            })
            ->where(function($query) use ($searchQuery) {
                $query->where('content', 'like', "%{$searchQuery}%")
                      ->orWhere('subject', 'like', "%{$searchQuery}%")
                      ->orWhere('body', 'like', "%{$searchQuery}%");
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'status' => 'success',
            'query' => $searchQuery,
            'results' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->fullName(),
                    'receiver_id' => $message->receiver_id,
                    'created_at' => $message->created_at->toISOString(),
                    'formatted_time' => $message->created_at->format('M j, Y g:i A'),
                ];
            }),
            'count' => $messages->count()
        ]);
    }

    /**
     * Mute notifications for a conversation
     */
    public function muteConversation(User $user, Request $request)
    {
        $currentUser = Auth::user();

        // Get or create the conversation between these users
        $conversation = Conversation::where(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $currentUser->id)
                      ->where('user2_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', $currentUser->id);
            })
            ->first();

        // Create conversation if it doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $currentUser->id,
                'user2_id' => $user->id,
            ]);
        }

        // Check if already muted
        $existingMute = MutedConversation::where('user_id', $currentUser->id)
            ->where('conversation_id', $conversation->id)
            ->active()
            ->first();

        if ($existingMute) {
            return response()->json(['status' => 'error', 'message' => 'Conversation is already muted'], 400);
        }

        // Determine mute duration
        $duration = $request->input('duration', 'forever'); // forever, 1hour, 8hours, 24hours, 1week
        $mutedUntil = null;

        switch ($duration) {
            case '1hour':
                $mutedUntil = now()->addHour();
                break;
            case '8hours':
                $mutedUntil = now()->addHours(8);
                break;
            case '24hours':
                $mutedUntil = now()->addDay();
                break;
            case '1week':
                $mutedUntil = now()->addWeek();
                break;
            default:
                $mutedUntil = null; // forever
        }

        MutedConversation::create([
            'user_id' => $currentUser->id,
            'conversation_id' => $conversation->id,
            'muted_until' => $mutedUntil,
        ]);

        $message = $mutedUntil
            ? 'Notifications muted until ' . $mutedUntil->format('M j, Y g:i A')
            : 'Notifications muted indefinitely';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'muted_until' => $mutedUntil?->toISOString()
        ]);
    }

    /**
     * Unmute notifications for a conversation
     */
    public function unmuteConversation(User $user)
    {
        $currentUser = Auth::user();

        // Get the conversation between these users
        $conversation = Conversation::where(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $currentUser->id)
                      ->where('user2_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', $currentUser->id);
            })
            ->first();

        // If no conversation exists, nothing to unmute
        if (!$conversation) {
            return response()->json(['status' => 'success', 'message' => 'Notifications unmuted']);
        }

        // Find and delete the mute
        $mute = MutedConversation::where('user_id', $currentUser->id)
            ->where('conversation_id', $conversation->id)
            ->active()
            ->first();

        if (!$mute) {
            return response()->json(['status' => 'error', 'message' => 'Conversation is not muted'], 400);
        }

        $mute->delete();

        return response()->json(['status' => 'success', 'message' => 'Notifications unmuted']);
    }

    /**
     * Check if a conversation is muted
     */
    public function isMuted(User $user)
    {
        $currentUser = Auth::user();

        $conversation = Conversation::where(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $currentUser->id)
                      ->where('user2_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('user1_id', $user->id)
                      ->where('user2_id', $currentUser->id);
            })
            ->first();

        if (!$conversation) {
            return response()->json(['is_muted' => false]);
        }

        $isMuted = MutedConversation::where('user_id', $currentUser->id)
            ->where('conversation_id', $conversation->id)
            ->active()
            ->exists();

        return response()->json(['is_muted' => $isMuted]);
    }

    protected function getConversations($user)
    {
        return Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function($messages) use ($user) {
                $otherUser = $messages->first()->sender_id == $user->id 
                    ? $messages->first()->receiver 
                    : $messages->first()->sender;
                
                return (object)[
                    'user' => $otherUser,
                    'last_message' => $messages->sortByDesc('created_at')->first(),
                    'unread_count' => $messages->where('receiver_id', $user->id)->whereNull('read_at')->count()
                ];
            });
    }
    
    /**
     * Start a new conversation with a user
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startConversation(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if there's an existing conversation
        $existingMessage = Message::where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->first();
            
        if ($existingMessage) {
            // If conversation exists, redirect to the existing conversation
            return redirect()->route('messages.show', $user->id);
        }
        
        // If no existing conversation, redirect to the message page with a welcome message
        return redirect()->route('messages.show', $user->id)->with('show_welcome', true);
    }
}
