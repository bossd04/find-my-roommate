<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        // Get all messages with their sender and receiver
        $messagesQuery = Message::with(['sender', 'receiver']);
        
        // Apply status filter if provided
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $messagesQuery->where('is_read', false);
            } elseif ($request->status === 'read') {
                $messagesQuery->where('is_read', true);
            }
        }
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $messagesQuery->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('receiver', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get all messages ordered by latest first
        $allMessages = $messagesQuery->latest()->get();
        
        // Group messages by conversation (unique sender-receiver pairs)
        $conversations = $allMessages->groupBy(function($message) {
            // Create a unique key for each conversation pair (sorted to ensure consistency)
            $userIds = [$message->sender_id, $message->receiver_id];
            sort($userIds); // Sort so that [1,2] and [2,1] become the same group
            return implode('-', $userIds);
        })->map(function($messages) {
            // Get the latest message from this conversation
            $latestMessage = $messages->sortByDesc('created_at')->first();
            
            // Count unread messages in this conversation
            $unreadCount = $messages->where('is_read', false)->count();
            
            // Get total message count in conversation
            $totalCount = $messages->count();
            
            return (object)[
                'id' => $latestMessage->id, // Use latest message ID for link
                'sender' => $latestMessage->sender,
                'receiver' => $latestMessage->receiver,
                'subject' => $latestMessage->subject,
                'body' => $latestMessage->body,
                'image' => $latestMessage->image,
                'is_read' => $latestMessage->is_read,
                'created_at' => $latestMessage->created_at,
                'unread_count' => $unreadCount,
                'total_count' => $totalCount,
                'latest_message' => $latestMessage,
            ];
        })->values(); // Reset keys to get sequential array
        
        // Manual pagination for the conversation collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $total = $conversations->count();
        
        $conversations = $conversations->forPage($currentPage, $perPage);
        
        $messages = new \Illuminate\Pagination\LengthAwarePaginator(
            $conversations,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $unreadCount = Message::where('is_read', false)->count();
        
        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(Message $message)
    {
        // Mark the current message as read if unread
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        // Get the two users in this conversation
        $user1Id = $message->sender_id;
        $user2Id = $message->receiver_id;
        
        // Fetch all messages between these two users (the full conversation)
        $conversationMessages = Message::with(['sender', 'receiver'])
            ->where(function($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user1Id)
                      ->where('receiver_id', $user2Id);
            })
            ->orWhere(function($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)
                      ->where('receiver_id', $user1Id);
            })
            ->orderBy('created_at', 'asc') // Oldest first for chat view
            ->get();
        
        // Mark all unread messages in this conversation as read
        Message::whereIn('id', $conversationMessages->where('is_read', false)->pluck('id'))
            ->update(['is_read' => true]);
        
        // Get conversation participants
        $participant1 = $message->sender;
        $participant2 = $message->receiver;
        
        return view('admin.messages.show', compact('message', 'conversationMessages', 'participant1', 'participant2'));
    }

   public function create()
{
    $users = User::where('id', '!=', auth()->id())->get();
    return view('admin.messages.create', compact('users'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'content' => $validated['body'],
            'is_read' => false,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image->getClientOriginalName());
            $path = $image->storeAs('public/messages', $imageName, 'local');
            $data['image'] = $path; // Store full path including 'public/'
        }

        $message = Message::create($data);

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message sent successfully');
    }

    public function markAsRead(Message $message)
    {
        $message->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Message::where('recipient_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('success', 'All messages marked as read');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted successfully');
    }
}


