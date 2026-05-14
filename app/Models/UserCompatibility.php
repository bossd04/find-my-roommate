<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCompatibility extends Model
{
    protected $fillable = [
        'user_id',
        'target_user_id',
        'compatibility_score',
        'interaction_count',
        'profile_views',
        'messages_exchanged',
        'preference_matches',
        'last_interaction_at',
        'is_fully_compatible',
    ];

    protected $casts = [
        'compatibility_score' => 'integer',
        'interaction_count' => 'integer',
        'profile_views' => 'integer',
        'messages_exchanged' => 'integer',
        'preference_matches' => 'integer',
        'last_interaction_at' => 'datetime',
        'is_fully_compatible' => 'boolean',
    ];

    /**
     * Get the user that owns the compatibility record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the target user.
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Increment interaction score based on interaction type
     */
    public function addInteraction(string $type): void
    {
        \DB::transaction(function () use ($type) {
            $this->interaction_count++;
            $this->last_interaction_at = now();
            
            // Re-calculate matches if not already set or periodically to ensure accuracy
            if ($this->preference_matches === 0) {
                // Ensure users and their preferences/profiles are loaded
                $user = User::with(['preference', 'roommateProfile'])->find($this->user_id);
                $targetUser = User::with(['preference', 'roommateProfile'])->find($this->target_user_id);

                if ($user && $targetUser && $user->preference && $targetUser->preference) {
                    $matches = 0;
                    $prefKeys = [
                        'cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance', 
                        'schedule', 'overnight_visitors', 'smoking', 'pets'
                    ];
                    
                    foreach ($prefKeys as $key) {
                        if ($user->preference->$key !== null && $targetUser->preference->$key !== null && 
                            $user->preference->$key === $targetUser->preference->$key) {
                            $matches++;
                        }
                    }

                    // Budget overlap
                    if ($user->budget_min && $user->budget_max && $targetUser->budget_min && $targetUser->budget_max) {
                        $overlap = min($user->budget_max, $targetUser->budget_max) - max($user->budget_min, $targetUser->budget_min);
                        if ($overlap > 0) $matches++;
                    }

                    // Location match
                    $userLoc = $user->location ?: (optional($user->profile)->apartment_location);
                    $targetLoc = $targetUser->location ?: (optional($targetUser->profile)->apartment_location);
                    if ($userLoc && $targetLoc && strcasecmp(trim($userLoc), trim($targetLoc)) === 0) {
                        $matches++;
                    }
                    
                    $this->preference_matches = $matches;
                }
            }

            switch ($type) {
                case 'profile_view':
                    $this->profile_views++;
                    if ($this->preference_matches > 0) {
                        $this->compatibility_score = min(100, $this->compatibility_score + 5);
                    }
                    break;
                case 'message':
                    $this->messages_exchanged++;
                    $this->compatibility_score = min(100, $this->compatibility_score + 10);
                    break;
                case 'preference_match':
                    $this->compatibility_score = min(100, $this->compatibility_score + 15);
                    break;
                case 'like':
                    $this->compatibility_score = min(100, $this->compatibility_score + 10);
                    break;
                case 'violation':
                    $this->compatibility_score = max(0, $this->compatibility_score - 25);
                    $this->is_fully_compatible = false;
                    break;
            }

            if ($this->compatibility_score >= 100) {
                $this->is_fully_compatible = true;
            }

            $this->save();
        });
    }

    /**
     * Calculate base compatibility from preferences
     */
    public function calculateBaseCompatibility(User $user, User $targetUser): int
    {
        $baseScore = 0;
        
        if ($user->preference && $targetUser->preference) {
            $preferences = [
                'cleanliness_level' => 15,
                'sleep_pattern' => 12,
                'study_habit' => 8,
                'noise_tolerance' => 8,
                'schedule' => 8,
                'overnight_visitors' => 8,
                'smoking' => 12,
                'pets' => 7
            ];

            foreach ($preferences as $pref => $points) {
                if ($user->preference->$pref === $targetUser->preference->$pref) {
                    $baseScore += $points;
                }
            }
        }

        return $baseScore;
    }
}
