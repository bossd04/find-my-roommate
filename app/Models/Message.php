<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Services\CompatibilityService;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'content',
        'subject',
        'body',
        'image',
        'read_at',
        'is_delivered',
        'is_read',
        'type',
        'metadata',
        'message_type',
        'reaction',
        'delivery_status'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_delivered' => 'boolean',
        'is_read' => 'boolean',
        'metadata' => 'array',
    ];

    protected $with = ['sender', 'receiver', 'conversation'];

    protected $appends = [
        'is_own_message',
        'time_ago',
        'status_class'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::created(function ($message) {
            // Track message interaction for compatibility
            $sender = $message->sender;
            $receiver = $message->receiver;
            
            if ($sender && $receiver) {
                $compatibilityService = app(CompatibilityService::class);
                $compatibilityService->trackMessageInteraction($sender, $receiver);
            }
        });
    }

    /**
     * Get the subject attribute (accessor for backward compatibility).
     */
    public function getSubjectAttribute($value)
    {
        // If subject is stored, use it; otherwise generate from content
        if ($value) {
            return $value;
        }
        
        // Generate subject from content preview
        $content = $this->content ?? $this->body ?? '';
        if (empty($content)) {
            return 'No subject';
        }
        
        // Extract first line or first 50 characters
        $lines = explode("\n", $content);
        $firstLine = trim($lines[0]);
        
        if (strlen($firstLine) > 60) {
            return substr($firstLine, 0, 57) . '...';
        }
        
        return $firstLine ?: 'Message';
    }

    /**
     * Get the body attribute (accessor for backward compatibility).
     */
    public function getBodyAttribute($value)
    {
        // If body is stored, use it; otherwise use content
        if ($value) {
            return $value;
        }
        
        return $this->content ?? '';
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            // Update conversation's last message timestamp and increment unread count
            if ($message->conversation) {
                $message->conversation->update([
                    'last_message_at' => $message->created_at
                ]);
                
                if ($message->conversation->user1_id == $message->receiver_id) {
                    $message->conversation->increment('unread_count_user1');
                } else {
                    $message->conversation->increment('unread_count_user2');
                }
            }
        });
    }

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withDefault([
            'name' => 'Unknown User',
            'avatar' => 'https://ui-avatars.com/api/?name=Unknown&background=random',
        ]);
    }

    /**
     * Get the user that received the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id')->withDefault([
            'name' => 'Unknown User',
            'avatar' => 'https://ui-avatars.com/api/?name=Unknown&background=random',
        ]);
    }

    /**
     * Get the time ago in human readable format.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if the message was sent by the current user.
     */
    public function getIsOwnMessageAttribute(): bool
    {
        return $this->sender_id === Auth::id();
    }

    /**
     * Scope a query to only include messages between two users.
     */
    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)
              ->where('receiver_id', $user2Id);
        })->orWhere(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)
              ->where('receiver_id', $user1Id);
        });
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include messages for a specific conversation.
     */
    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
            
            // Update conversation unread count
            if ($this->conversation->user1_id == $this->receiver_id) {
                $this->conversation->decrement('unread_count_user1');
            } else {
                $this->conversation->decrement('unread_count_user2');
            }
        }
        
        return $this;
    }

    /**
     * Mark the message as delivered.
     */
    public function markAsDelivered()
    {
        if (!$this->is_delivered) {
            $this->is_delivered = true;
            $this->save();
        }
        
        return $this;
    }


    /**
     * Get the message status class for UI.
     */
    public function getStatusClassAttribute(): string
    {
        if ($this->is_read) {
            return 'read';
        }
        
        return $this->is_delivered ? 'delivered' : 'sent';
    }
}
