<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'status',
        'requested_at',
        'processed_at',
        'processed_by',
        'admin_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user who requested account deletion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the request.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if the request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the request is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Approve the deletion request.
     */
    public function approve(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Reject the deletion request.
     */
    public function reject(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Cancel the deletion request.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'processed_at' => now(),
        ]);
    }
}
