<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preference extends Model
{
    protected $fillable = [
        'user_id',
        'cleanliness_level',
        'sleep_pattern',
        'study_habit',
        'noise_tolerance',
        'min_budget',
        'max_budget',
        'hobbies',
        'lifestyle_tags',
        'smoking',
        'pets',
        'overnight_visitors',
        'schedule'
    ];

    protected $casts = [
        'hobbies' => 'array',
        'lifestyle_tags' => 'array',
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
