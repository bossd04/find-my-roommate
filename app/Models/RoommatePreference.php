<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoommatePreference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'preferred_gender',
        'min_age',
        'max_age',
        'preferred_cleanliness',
        'preferred_noise_level',
        'preferred_schedule',
        'smoking_ok',
        'pets_ok',
        'has_apartment_preferred',
        'preferred_location',
        'min_budget',
        'max_budget',
        'preferred_move_in_date',
        'preferred_lease_duration',
        'willing_to_share_room',
        'furnished_preferred',
        'utilities_included_preferred',
        'preferred_room_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_age' => 'integer',
        'max_age' => 'integer',
        'smoking_ok' => 'boolean',
        'pets_ok' => 'boolean',
        'has_apartment_preferred' => 'boolean',
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
        'willing_to_share_room' => 'boolean',
        'furnished_preferred' => 'boolean',
        'utilities_included_preferred' => 'boolean',
    ];

    /**
     * Get the user that owns the preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
