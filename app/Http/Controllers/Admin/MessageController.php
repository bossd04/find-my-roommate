<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['sender', 'receiver'])
            ->latest()
            ->paginate(15);
            
        $unreadCount = Message::where('is_read', false)->count();
        
        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(Message $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        
        return view('admin.messages.show', compact('message'));
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
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_read' => false,
        ]);

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


