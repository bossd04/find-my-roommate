<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_id',
        'report_type',
        'reason',
        'status',
        'admin_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reported(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'reviewing');
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'dismissed']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsReviewing(): void
    {
        $this->update(['status' => 'reviewing']);
    }

    public function markAsResolved(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_by' => $adminId,
            'resolved_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function markAsDismissed(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'dismissed',
            'resolved_by' => $adminId,
            'resolved_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function getReportTypeLabel(): string
    {
        $labels = [
            'inappropriate_behavior' => 'Inappropriate Behavior',
            'harassment' => 'Harassment',
            'spam' => 'Spam',
            'fake_profile' => 'Fake Profile',
            'scam' => 'Scam/Fraud',
            'other' => 'Other',
        ];

        return $labels[$this->report_type] ?? 'Unknown';
    }

    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Pending',
            'reviewing' => 'Under Review',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }
}
