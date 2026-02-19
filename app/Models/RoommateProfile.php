<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoommateProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'display_name',
        'age',
        'gender',
        'bio',
        'university',
        'major',
        'cleanliness_level',
        'noise_level',
        'schedule',
        'smoking_allowed',
        'pets_allowed',
        'has_apartment',
        'apartment_location',
        'phone',
        'budget_min',
        'budget_max',
        'move_in_date',
        'lease_duration',
        'sleep_pattern',
        'study_habit',
        'noise_tolerance',
        'hobbies',
        'lifestyle_tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
        'smoking_allowed' => 'boolean',
        'pets_allowed' => 'boolean',
        'has_apartment' => 'boolean',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'hobbies' => 'array',
        'lifestyle_tags' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
