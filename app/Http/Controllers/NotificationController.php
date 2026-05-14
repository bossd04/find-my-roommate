<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get all unread notifications for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications()->latest()->take(10)->get();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Mark all notifications as read for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}
