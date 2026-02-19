<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoommateMatch extends Model
{
    protected $fillable = [
        'user_id',
        'matched_user_id',
        'status',
        'user_action',
        'is_mutual'
    ];

    protected $casts = [
        'is_mutual' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->orWhere('matched_user_id', $userId);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
