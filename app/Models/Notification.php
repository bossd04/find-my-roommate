<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'message',
        'read_at',
        'data'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsRead(): bool
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
            return true;
        }
        return false;
    }
}
