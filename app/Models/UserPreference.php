<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'smoking',
        'drinking',
        'cleanliness',
        'schedule',
        'sleep_schedule',
        'has_pets',
        'pet_details',
        'has_children',
        'children_details',
        'occupation',
        'interests',
        'languages',
        'budget_min',
        'budget_max',
        'move_in_date',
        'lease_duration',
        'furnished',
        'parking',
        'laundry',
        'utilities_included',
        'room_type',
        'bathroom_type',
        'location_preferences',
    ];

    protected $casts = [
        'interests' => 'array',
        'languages' => 'array',
        'move_in_date' => 'date',
        'utilities_included' => 'boolean',
        'furnished' => 'boolean',
        'has_pets' => 'boolean',
        'has_children' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
