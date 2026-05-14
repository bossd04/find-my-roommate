<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutedConversation extends Model
{
    protected $fillable = [
        'user_id',
        'conversation_id',
        'muted_until',
    ];

    protected $casts = [
        'muted_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('muted_until')
              ->orWhere('muted_until', '>', now());
        });
    }

    public function isMuted(): bool
    {
        if ($this->muted_until === null) {
            return true;
        }
        return $this->muted_until->isFuture();
    }
}
