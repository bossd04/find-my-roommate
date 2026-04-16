<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact support page.
     */
    public function index()
    {
        return view('pages.contact-simple');
    }

    /**
     * Handle contact form submission (if you want to add a form later).
     */
    public function submit(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you would typically:
        // 1. Send an email to support
        // 2. Store in database
        // 3. Send notification to admin
        
        // For now, just return success message
        return redirect()->route('contact.support')
            ->with('success', 'Thank you for contacting us! We will get back to you within 24 hours.');
    }

    /**
     * Handle admin contact from listing page.
     */
    public function contactAdmin(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'listing_id' => 'required|integer|exists:listings,id',
            'admin_id' => 'required|integer|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $admin = User::find($validated['admin_id']);
        $listing = Listing::find($validated['listing_id']);

        if (!$currentUser) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to contact admin.'
            ], 401);
        }

        if (!$admin || !$admin->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin user.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Get or create conversation between user and admin
            $user1Id = min($currentUser->id, $admin->id);
            $user2Id = max($currentUser->id, $admin->id);

            $conversation = Conversation::firstOrCreate(
                ['user1_id' => $user1Id, 'user2_id' => $user2Id],
                ['last_message_at' => now()]
            );

            // Create the message
            $message = new Message([
                'sender_id' => $currentUser->id,
                'receiver_id' => $admin->id,
                'content' => $validated['message'] . "\n\n[Regarding listing: " . $listing->title . " (ID: " . $listing->id . ")]",
                'message_type' => 'text',
                'delivery_status' => 'delivered',
                'is_delivered' => true,
                'is_read' => false,
                'metadata' => json_encode([
                    'source' => 'listing_contact',
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->title
                ])
            ]);

            $message->conversation()->associate($conversation);
            $message->save();

            // Update conversation's last message timestamp
            $conversation->update(['last_message_at' => $message->created_at]);

            // Increment unread count for admin
            if ($conversation->user1_id == $admin->id) {
                $conversation->increment('unread_count_user1');
            } else {
                $conversation->increment('unread_count_user2');
            }

            DB::commit();

            // Optionally send email notification to admin
            // Mail::to($admin->email)->send(new AdminContactNotification($message, $listing, $currentUser));

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => [
                    'message_id' => $message->id,
                    'conversation_id' => $conversation->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin contact error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }
}
