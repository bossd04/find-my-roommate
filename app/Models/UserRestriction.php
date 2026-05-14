<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRestriction extends Model
{
    protected $fillable = [
        'restricted_id',
        'restricted_by',
        'restriction_type',
        'reason',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function restricted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restricted_id');
    }

    public function restrictor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restricted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if ($this->expires_at === null) {
            return true;
        }
        return $this->expires_at->isFuture();
    }

    public function getRestrictionTypeLabel(): string
    {
        $labels = [
            1 => 'Limited Messaging',
            2 => 'No New Matches',
            3 => 'Read Only',
            4 => 'Account Suspension',
        ];

        return $labels[$this->restriction_type] ?? 'Unknown';
    }

    public function getDescription(): string
    {
        $descriptions = [
            1 => 'User can only send messages to existing connections',
            2 => 'User cannot create new matches or connections',
            3 => 'User can only read messages but cannot respond',
            4 => 'User account is fully suspended',
        ];

        return $descriptions[$this->restriction_type] ?? 'Unknown restriction';
    }
}
