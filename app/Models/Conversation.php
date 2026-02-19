<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message_at',
        'unread_count_user1',
        'unread_count_user2',
    ];
    
    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            // Ensure user1_id is always less than user2_id to prevent duplicate conversations
            if ($conversation->user1_id > $conversation->user2_id) {
                $temp = $conversation->user1_id;
                $conversation->user1_id = $conversation->user2_id;
                $conversation->user2_id = $temp;
            }
        });
    }

    protected $dates = [
        'last_message_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the first user in the conversation.
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Get the second user in the conversation.
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Get all messages in the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the other user in the conversation.
     */
    public function otherUser($userId)
    {
        return $this->user1_id == $userId ? $this->user2 : $this->user1;
    }

    /**
     * Get the unread count for a specific user.
     */
    public function unreadCountForUser($userId)
    {
        if ($this->user1_id == $userId) {
            return $this->unread_count_user1;
        } elseif ($this->user2_id == $userId) {
            return $this->unread_count_user2;
        }
        return 0;
    }

    /**
     * Mark messages as read for a specific user.
     */
    public function markAsRead($userId)
    {
        if ($this->user1_id == $userId) {
            $this->unread_count_user1 = 0;
        } elseif ($this->user2_id == $userId) {
            $this->unread_count_user2 = 0;
        }
        $this->save();
    }

    /**
     * Increment unread count for a specific user.
     */
    public function incrementUnreadCount($userId)
    {
        if ($this->user1_id == $userId) {
            $this->increment('unread_count_user1');
        } elseif ($this->user2_id == $userId) {
            $this->increment('unread_count_user2');
        }
    }
}
