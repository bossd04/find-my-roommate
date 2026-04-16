<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        'caller_id',
        'receiver_id',
        'call_type',
        'status',
        'started_at',
        'ended_at',
        'duration_seconds',
        'offer_sdp',
        'answer_sdp',
        'ice_candidate',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the caller user
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    /**
     * Get the receiver user
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Check if call is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if call is connected
     */
    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }

    /**
     * Check if call has ended
     */
    public function hasEnded(): bool
    {
        return in_array($this->status, ['ended', 'declined', 'missed']);
    }

    /**
     * Mark call as connected
     */
    public function markAsConnected(): void
    {
        $this->update([
            'status' => 'connected',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark call as ended
     */
    public function markAsEnded(): void
    {
        $endedAt = now();
        $duration = $this->started_at ? $endedAt->diffInSeconds($this->started_at) : null;

        $this->update([
            'status' => 'ended',
            'ended_at' => $endedAt,
            'duration_seconds' => $duration,
        ]);
    }

    /**
     * Mark call as declined
     */
    public function markAsDeclined(): void
    {
        $this->update([
            'status' => 'declined',
            'ended_at' => now(),
        ]);
    }

    /**
     * Mark call as missed
     */
    public function markAsMissed(): void
    {
        $this->update([
            'status' => 'missed',
            'ended_at' => now(),
        ]);
    }

    /**
     * Check if call was pending
     */
    public function wasPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration(): string
    {
        if (!$this->duration_seconds) {
            return '0:00';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Scope for active calls
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'connected']);
    }

    /**
     * Scope for pending calls
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
