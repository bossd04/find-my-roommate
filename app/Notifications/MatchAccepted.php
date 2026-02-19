<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatchAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user who accepted the match
     *
     * @var User
     */
    public $accepter;

    /**
     * Create a new notification instance.
     *
     * @param User $accepter The user who accepted the match
     * @return void
     */
    public function __construct(User $accepter)
    {
        $this->accepter = $accepter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Match Has Been Accepted!')
                    ->greeting('Hello ' . $notifiable->first_name . '!')
                    ->line($this->accepter->fullName() . ' has accepted your match request.')
                    ->action('Start Chatting', route('messages.show', $this->accepter))
                    ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->accepter->fullName() . ' has accepted your match request!',
            'url' => route('messages.show', $this->accepter),
            'type' => 'match_accepted',
            'user_id' => $this->accepter->id,
            'user_name' => $this->accepter->fullName(),
            'user_photo' => $this->accepter->profile_photo_url,
        ];
    }
}
