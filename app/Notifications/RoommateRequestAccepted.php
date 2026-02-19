<?php

namespace App\Notifications;

use App\Models\RoommateMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoommateRequestAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $match;

    /**
     * Create a new notification instance.
     */
    public function __construct(RoommateMatch $match)
    {
        $this->match = $match;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'roommate_request_accepted',
            'message' => 'Your roommate request with ' . $this->match->matchedUser->name . ' has been accepted! Start your conversation now!',
            'match_id' => $this->match->id,
            'user_id' => $this->match->user_id,
            'matched_user_id' => $this->match->matched_user_id,
        ];
    }
}
