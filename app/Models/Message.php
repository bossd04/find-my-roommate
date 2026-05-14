<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'role',
        'content',
        'message_type',
        'delivery_status',
        'is_delivered',
        'is_read',
        'read_at',
        'metadata',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withTrashed();
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id')->withTrashed();
    }

    /**
     * Helper to check if message is from the user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Helper to check if message is from the assistant.
     */
    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }
}
