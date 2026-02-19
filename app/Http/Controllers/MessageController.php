<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
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
        
        // Get all conversations for the current user with unread counts and latest message
        $conversations = Message::select([
                'messages.*',
                DB::raw('COUNT(CASE WHEN messages.receiver_id = ' . $currentUser->id . ' AND messages.read_at IS NULL THEN 1 END) as unread_count')
            ])
            ->where(function($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->groupBy(DB::raw('CASE WHEN sender_id = ' . $currentUser->id . ' THEN receiver_id ELSE sender_id END'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($message) use ($currentUser) {
                $otherUser = $message->sender_id == $currentUser->id 
                    ? $message->receiver 
                    : $message->sender;
                
                return (object)[
                    'otherUser' => $otherUser,
                    'latestMessage' => $message,
                    'unread_count' => $message->unread_count
                ];
            });

        // If a user is selected, get the conversation
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
                
            // Broadcast read event
            broadcast(new MessageRead($currentUser, $user));
        }

        return view('messages.chat', [
            'conversations' => $conversations,
            'selectedUser' => $selectedUser,
            'messages' => $messages
        ]);
    }
    
    
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id === Auth::id() && !$message->read_at) {
            $message->update(['read_at' => now()]);
            broadcast(new MessageRead(Auth::user(), $message->sender));
        }
        
        return response()->json(['status' => 'Message marked as read']);
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Ensure users can only message other users (not admins)
        if ($user->is_admin || $currentUser->is_admin) {
            abort(403, 'Admin messaging is not allowed.');
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

        return view('messages.show', [
            'messages' => $messages,
            'conversations' => $conversations,
            'receiver' => $user,
            'compatibility' => $compatibility,
            'currentUser' => $currentUser
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
        
        // Ensure users can only message other users (not admins)
        if ($user->is_admin || $currentUser->is_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admin messaging is not allowed.'
                ], 403);
            }
            return back()->with('error', 'Admin messaging is not allowed.');
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
            
            // Broadcast the message
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
