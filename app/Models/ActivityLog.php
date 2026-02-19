<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event',
        'ip_address',
        'user_agent',
        'method',
        'route',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that caused the activity.
     */
    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the human-readable description of the activity.
     */
    public function getDescriptionAttribute($value): string
    {
        return ucfirst($value);
    }

    /**
     * Get the icon for the activity type.
     */
    public function getIconAttribute(): string
    {
        return match($this->event) {
            'created' => 'fa-plus-circle text-green-500',
            'updated' => 'fa-edit text-blue-500',
            'deleted' => 'fa-trash-alt text-red-500',
            'restored' => 'fa-trash-restore text-yellow-500',
            'forceDeleted' => 'fa-trash text-red-700',
            default => 'fa-info-circle text-gray-500',
        };
    }

    /**
     * Scope a query to only include activities by a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('causer_id', $userId);
    }

    /**
     * Scope a query to only include activities for a specific subject.
     */
    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject_type', get_class($subject))
                    ->where('subject_id', $subject->id);
    }

    /**
     * Log an activity.
     */
    public static function log(string $description, $subject = null, array $properties = [], string $event = null): self
    {
        $request = request();
        
        return static::create([
            'log_name' => config('activitylog.default_log_name', 'default'),
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
            'event' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'route' => $request->path(),
        ]);
    }

    /**
     * Get the activity's human-readable name.
     */
    public function getEventNameAttribute(): string
    {
        return match($this->event) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
            'forceDeleted' => 'Permanently Deleted',
            default => 'Performed Action',
        };
    }

    /**
     * Get the activity's CSS class based on the event type.
     */
    public function getEventClassAttribute(): string
    {
        return match($this->event) {
            'created' => 'bg-green-100 text-green-800',
            'updated' => 'bg-blue-100 text-blue-800',
            'deleted' => 'bg-red-100 text-red-800',
            'restored' => 'bg-yellow-100 text-yellow-800',
            'forceDeleted' => 'bg-red-800 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
