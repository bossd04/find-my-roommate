<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Preference;
use App\Models\Department;
use App\Models\Course;
use App\Models\UserBlock;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'looking_for_roommate' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'location',
        'profile_photo_path',
        'avatar',
        'last_login_at',
        'last_seen',
        'email_verified_at',
        'is_admin',
        'is_active',
        'is_approved',
        'looking_for_roommate',
        'bio',
        'date_of_birth',
        'gender',
        'age',
        'university',
        'department',
        'course',
        'year_level',
        'occupation',
        'move_in_date',
        'budget_min',
        'budget_max',
        'hobbies',
        'lifestyle_tags',
        'preferred_location',
        'preferred_lease_length',
        'id_card_front',
        'id_card_back',
        'verification_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'move_in_date' => 'date',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'last_seen' => 'datetime',
        'last_login_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'looking_for_roommate' => 'boolean',
        'profile_completed_redirect' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_online',
        'profile_photo_url',
        'full_name',
    ];

    /**
     * Get the roommate profile associated with the user.
     */
    public function roommateProfile()
    {
        return $this->hasOne(RoommateProfile::class);
    }

    /**
     * The relationships that should always be loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['preference'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($user) {
            $user->name = trim($user->first_name . ' ' . $user->last_name);
        });

        static::created(function ($user) {
            $user->preference()->create([
                'cleanliness_level' => 'average',
                'sleep_pattern' => 'flexible',
                'study_habit' => 'no_preference',
                'noise_tolerance' => 'moderate',
                'min_budget' => 0,
                'max_budget' => 0,
                'smoking' => 'never',
                'pets' => 'none',
                'overnight_visitors' => 'with_notice',
                'schedule' => 'morning'
            ]);
            
            // Set all new users as looking for roommate by default
            $user->update(['looking_for_roommate' => true]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->name) {
            return $this->name;
        }
        
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!$this->profile_photo_path) {
            return $this->defaultProfilePhotoUrl();
        }
        
        // Check if the file exists in the public storage
        $path = storage_path('app/public/' . $this->profile_photo_path);
        if (!file_exists($path)) {
            return $this->defaultProfilePhotoUrl();
        }
        
        // Use direct route to serve image (fixes Windows symlink issues)
        $filename = basename($this->profile_photo_path);
        return route('profile.photo.serve', ['filename' => $filename]) . '?v=' . filemtime($path);
    }
    
    /**
     * Get the user's avatar URL.
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            try {
                // Get just the filename from the avatar path
                $filename = basename($this->avatar);
                
                // Check multiple possible paths
                $paths = [
                    storage_path('app/public/avatars/' . $filename),
                    storage_path('app/private/public/avatars/' . $filename),
                    storage_path('app/public/' . $this->avatar),
                ];
                
                foreach ($paths as $path) {
                    if (file_exists($path)) {
                        return route('avatar.serve', ['filename' => $filename]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error accessing avatar file', [
                    'user_id' => $this->id,
                    'avatar_path' => $this->avatar,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return null;
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function fullName(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        
        return $this->name;
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultProfilePhotoUrl(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Check if the user is currently online.
     * 
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    /**
     * Get the is_online attribute.
     * 
     * @return bool
     */
    public function getIsOnlineAttribute(): bool
    {
        return $this->isOnline();
    }

    /**
     * Get the user's age from their date of birth.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        $age = now()->diffInYears($this->date_of_birth);
        return $age !== null ? abs((int) $age) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the listings created by the user.
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all matches initiated by this user
     */
    public function matches(): HasMany
    {
        return $this->hasMany(RoommateMatch::class, 'user_id');
    }

    /**
     * Get all matches where this user was matched by someone else
     */
    public function matchedBy(): HasMany
    {
        return $this->hasMany(RoommateMatch::class, 'matched_user_id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the user's preference.
     */
    public function preference(): HasOne
    {
        return $this->hasOne(Preference::class);
    }

    /**
     * Get the user's activities.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    /**
     * Get the user's conversations.
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user1_id')
            ->orWhere('user2_id', $this->id);
    }

    /**
     * Get the user's notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    /**
     * Get the user's roommate profile.
     */
    public function profile()
    {
        return $this->hasOne(RoommateProfile::class, 'user_id');
    }

    /**
     * Get the courses the user has taken.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user's ID validation.
     */
    public function userValidation()
    {
        return $this->hasOne(UserValidation::class);
    }
    
    /**
     * Get the user's preferences.
     */
    public function preferences()
    {
        return $this->hasOne(Preference::class);
    }

    /**
     * Get the user's roommate preferences.
     */
    public function roommatePreference(): HasOne
    {
        return $this->hasOne(RoommatePreference::class);
    }

    /**
     * Get the user's compatibility records with other users.
     */
    public function compatibilities(): HasMany
    {
        return $this->hasMany(UserCompatibility::class, 'user_id');
    }

    /**
     * Get compatibility record with a specific user.
     */
    public function getCompatibilityWith(User $targetUser): UserCompatibility
    {
        try {
            $compatibility = $this->compatibilities()
                ->where('target_user_id', $targetUser->id)
                ->first();
                
            if (!$compatibility) {
                $compatibility = new UserCompatibility([
                    'target_user_id' => $targetUser->id,
                    'compatibility_score' => 0,
                    'interaction_count' => 0,
                    'profile_views' => 0,
                    'messages_exchanged' => 0,
                    'preference_matches' => 0,
                    'is_fully_compatible' => false,
                ]);
                $this->compatibilities()->save($compatibility);
            }
            
            return $compatibility;
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Handle race condition - record might have been created by another process
            return $this->compatibilities()
                ->where('target_user_id', $targetUser->id)
                ->firstOrFail();
        }
    }

    /**
     * Get the user's matches where they are the initiator.
     */
    public function sentMatches(): HasMany
    {
        return $this->hasMany(RoommateMatch::class, 'user_id');
    }

    /**
     * Get the user's matches where they are the recipient.
     */
    public function receivedMatches(): HasMany
    {
        return $this->hasMany(RoommateMatch::class, 'matched_user_id');
    }

    /**
     * Get all matches for the user.
     */
    public function allMatches()
    {
        return RoommateMatch::where('user_id', $this->id)
            ->orWhere('matched_user_id', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Other Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate compatibility score with another user based on progressive interaction tracking.
     */
    public function calculateCompatibilityScore(User $otherUser): array
    {
        // Get or create compatibility record
        $compatibility = $this->getCompatibilityWith($otherUser);
        
        // Calculate detailed matching preferences for display
        $matchingPreferences = $this->getDetailedMatchingPreferences($otherUser);
        
        return [
            'score' => $compatibility->compatibility_score,
            'total_possible' => 100,
            'earned_score' => $compatibility->compatibility_score,
            'interaction_data' => [
                'interaction_count' => $compatibility->interaction_count,
                'profile_views' => $compatibility->profile_views,
                'messages_exchanged' => $compatibility->messages_exchanged,
                'preference_matches' => $compatibility->preference_matches,
                'is_fully_compatible' => $compatibility->is_fully_compatible,
            ],
            'details' => $matchingPreferences
        ];
    }
    
    /**
     * Calculate base compatibility from preferences (initial calculation)
     */
    private function calculateBaseCompatibilityFromPreferences(User $otherUser): int
    {
        $baseScore = 0;
        
        // Return 0 if either user hasn't set up preferences yet
        if (!$this->preference || !$otherUser->preference) {
            return 0;
        }
        
        // Base matching points
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

        $matches = 0;
        foreach ($preferences as $pref => $points) {
            if ($this->preference->$pref === $otherUser->preference->$pref) {
                $baseScore += $points;
                $matches++;
            }
        }

        // Only give points if there's at least some basic match
        if ($matches === 0) {
            return 0;
        }
        return 0;
    }
    
    /**
     * Calculate conversation-based matching score (0% start, grows with messages)
     * Requested by user for the roommates page
     */
    public function calculateConversationScore(User $otherUser): int
    {
        $compatibility = $this->getCompatibilityWith($otherUser);
        $hasMatch = \App\Models\RoommateMatch::where(function($q) use ($otherUser) {
                $q->where('user_id', $this->id)->where('matched_user_id', $otherUser->id);
            })->orWhere(function($q) use ($otherUser) {
                $q->where('user_id', $otherUser->id)->where('matched_user_id', $this->id);
            })->where('status', 'accepted')->exists();

        // Starts at 0, grows with messages and mutual acceptance
        // Base score: 10% per message
        $score = $compatibility->messages_exchanged * 10;
        
        // Match bonus: if they have accepted each other, add preference-based matching bonus
        if ($hasMatch || $compatibility->messages_exchanged > 0) {
            $prefScore = $this->calculatePreferenceMatchingScore($otherUser);
            // Add half of the preference score as a "Lifestyle Harmony" bonus (up to 50%)
            $score += round($prefScore * 0.5);
        }

        return min(100, $score);
    }

    /**
     * Calculate static preference-based matching score (100-point system)
     * Requested by user for the matches page
     */
    public function calculatePreferenceMatchingScore(User $otherUser): int
    {
        // Ensure matching is only performed if both users have a preference record
        if (!$this->preference || !$otherUser->preference) {
            return 0;
        }

        // Calculate score based on weighted categories
        $weights = [
            'cleanliness_level' => 20,
            'sleep_pattern' => 15,
            'study_habit' => 10,
            'noise_tolerance' => 10,
            'schedule' => 10,
            'overnight_visitors' => 10,
            'smoking' => 15,
            'pets' => 10
        ];

        $score = 0;
        foreach ($weights as $key => $weight) {
            // Use string casting to ensure robust comparison across database drivers
            $myChoice = (string)($this->preference->$key ?? '');
            $theirChoice = (string)($otherUser->preference->$key ?? '');
            
            if (!empty($myChoice) && !empty($theirChoice) && $myChoice === $theirChoice) {
                $score += $weight;
            }
        }

        return $score;
    }

    /**
     * Get detailed matching preferences for display
     */
    public function getDetailedMatchingPreferences(User $otherUser): array
    {
        $matchingPreferences = [];
        
        if ($this->preference && $otherUser->preference) {
            $preferenceWeights = [
                'cleanliness_level' => 20,
                'sleep_pattern' => 15,
                'study_habit' => 10,
                'noise_tolerance' => 10,
                'schedule' => 10,
                'overnight_visitors' => 10,
                'smoking' => 15,
                'pets' => 10
            ];
            
            foreach ($preferenceWeights as $key => $weight) {
                if ($this->preference->$key === $otherUser->preference->$key) {
                    $matchingPreferences[$key] = [
                        'your_choice' => $this->preference->$key,
                        'their_choice' => $otherUser->preference->$key,
                        'match' => true,
                        'score' => $weight
                    ];
                } else {
                    $matchingPreferences[$key] = [
                        'your_choice' => $this->preference->$key ?? 'Not set',
                        'their_choice' => $otherUser->preference->$key ?? 'Not set',
                        'match' => false,
                        'score' => 0
                    ];
                }
            }
            
            // Budget compatibility (additional info, not in the 100pt base)
            if ($this->budget_min && $this->budget_max && $otherUser->budget_min && $otherUser->budget_max) {
                $budgetOverlap = min($this->budget_max, $otherUser->budget_max) - max($this->budget_min, $otherUser->budget_min);
                if ($budgetOverlap > 0) {
                    $matchingPreferences['budget'] = [
                        'your_range' => 'PHP ' . number_format((float)$this->budget_min, 0) . ' - ' . number_format((float)$this->budget_max, 0),
                        'their_range' => 'PHP ' . number_format((float)$otherUser->budget_min, 0) . ' - ' . number_format((float)$otherUser->budget_max, 0),
                        'match' => true,
                        'is_extra' => true
                    ];
                }
            }
        }
        
        return $matchingPreferences;
    }

    /**
     * Check if user profile is complete.
     */
    public function isProfileComplete(): bool
    {
        // Required fields for profile completion
        $requiredFields = [
            'first_name',
            'last_name', 
            'email',
            'phone',
            'gender',
            'date_of_birth',
            'location',
            'university',
            'course',
            'year_level'
        ];

        foreach ($requiredFields as $field) {
            if ($field === 'location') {
                if (empty($this->location) && empty(optional($this->roommateProfile)->apartment_location)) {
                    return false;
                }
                continue;
            }
            if (empty($this->$field)) {
                return false;
            }
        }

        // Check if roommate profile exists and has required fields
        if (!$this->profile) {
            return false;
        }

        $requiredProfileFields = [
            'cleanliness_level',
            'sleep_pattern',
            'study_habit',
            'noise_tolerance'
        ];

        foreach ($requiredProfileFields as $field) {
            if (empty($this->profile->$field)) {
                return false;
            }
        }

        // Check if budget is set
        if (empty($this->budget_min) || empty($this->budget_max)) {
            return false;
        }

        // ID: both sides uploaded and submitted for review or approved (admin may still be reviewing)
        if (
            empty($this->id_card_front)
            || empty($this->id_card_back)
            || !in_array($this->verification_status ?? '', ['pending', 'approved'], true)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check if user is verified through ID validation.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Structured data for admin ID verification modal (full profile + ID metadata).
     */
    public function toAdminIdReviewPayload(): array
    {
        $p = $this->roommateProfile;
        $v = $this->userValidation;

        $location = $this->location ?: ($p?->apartment_location ?? '');
        $budgetMin = $this->budget_min ?? $p?->budget_min;
        $budgetMax = $this->budget_max ?? $p?->budget_max;
        $budgetLabel = ($budgetMin !== null && $budgetMin !== '' && $budgetMax !== null && $budgetMax !== '')
            ? '₱' . number_format((float) $budgetMin, 0) . ' – ₱' . number_format((float) $budgetMax, 0)
            : '';

        $idTypeKey = $v?->id_type ?? '';
        $idTypeLabels = [
            'national_id' => 'National ID',
            'government_id' => 'Government ID',
            'umid_id' => 'UMID',
            'passport' => 'Passport',
            'drivers_license' => "Driver's License",
            'driver_license' => "Driver's License",
            'student_id' => 'Student ID',
            'other' => 'Other',
        ];
        $idTypeLabel = $idTypeLabels[$idTypeKey] ?? ($idTypeKey ? ucfirst(str_replace('_', ' ', $idTypeKey)) : '—');

        $hobbiesStr = $this->formatAdminListField($this->hobbies);
        if ($hobbiesStr === '—' && $p?->hobbies) {
            $hobbiesStr = $this->formatAdminListField($p->hobbies);
        }

        $tagsStr = $this->formatAdminListField($this->lifestyle_tags);
        if ($tagsStr === '—' && $p?->lifestyle_tags) {
            $tagsStr = $this->formatAdminListField($p->lifestyle_tags);
        }

        $dob = $this->date_of_birth
            ? \Carbon\Carbon::parse($this->date_of_birth)->format('M j, Y')
            : '—';

        return [
            'userId' => $this->id,
            'userName' => trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: ($this->name ?? '—'),
            'userEmail' => $this->email ?? '—',
            'userPhone' => $this->phone ?? '—',
            'userGender' => $this->gender ? ucfirst(str_replace('_', ' ', (string) $this->gender)) : '—',
            'userAge' => $this->age !== null ? (string) abs($this->age) : '—',
            'userDateOfBirth' => $dob,
            'userLocation' => $location ?: '—',
            'userPreferredLocation' => $this->preferred_location ?? '—',
            'userApartmentLocation' => $p?->apartment_location ?? '—',
            'userBudget' => $budgetLabel ?: '—',
            'userUniversity' => $this->university ?? '—',
            'userCourse' => $this->course ?? '—',
            'userYearLevel' => $this->year_level ?? '—',
            'userStudyHabit' => $p?->study_habit ? ucfirst(str_replace('_', ' ', (string) $p->study_habit)) : '—',
            'userSleepPattern' => $p?->sleep_pattern ? ucfirst(str_replace('_', ' ', (string) $p->sleep_pattern)) : '—',
            'userNoiseTolerance' => $p?->noise_tolerance ? ucfirst(str_replace('_', ' ', (string) $p->noise_tolerance)) : '—',
            'userLifestyle' => $tagsStr,
            'userHobbies' => $hobbiesStr,
            'userCleanliness' => $p?->cleanliness_level ? ucfirst(str_replace('_', ' ', (string) $p->cleanliness_level)) : '—',
            'userNoise' => $p?->noise_level ? ucfirst(str_replace('_', ' ', (string) $p->noise_level)) : '—',
            'userSchedule' => $p?->schedule ? ucfirst(str_replace('_', ' ', (string) $p->schedule)) : '—',
            'userSmoking' => $p && isset($p->smoking_allowed) ? ($p->smoking_allowed ? 'Yes' : 'No') : '—',
            'userPets' => $p && isset($p->pets_allowed) ? ($p->pets_allowed ? 'Yes' : 'No') : '—',
            'userHasApartment' => $p && isset($p->has_apartment) ? ($p->has_apartment ? 'Yes' : 'No') : '—',
            'userMoveInDate' => $this->formatAdminDate($p?->move_in_date)
                ?? $this->formatAdminDate($this->move_in_date)
                ?? '—',
            'userLeaseDuration' => $p?->lease_duration ? (string) $p->lease_duration : '—',
            'userBio' => $this->bio ?: ($p?->bio ?? '—'),
            'idType' => $idTypeLabel,
            'idNumber' => $v?->id_number ?? '—',
            'status' => $this->verification_status ?? 'pending',
            'imageFront' => $this->getIdImageUrl($this->id_card_front, $v?->id_front_image),
            'imageBack' => $this->getIdImageUrl($this->id_card_back, $v?->id_back_image),
            'avatarUrl' => $this->avatar_url ?? '',
        ];
    }

    private function formatAdminDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        try {
            if ($value instanceof Carbon) {
                return $value->format('M j, Y');
            }

            return Carbon::parse($value)->format('M j, Y');
        } catch (\Throwable) {
            return null;
        }
    }

    private function formatAdminListField(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        if (is_array($value)) {
            return implode(', ', array_filter(array_map('strval', $value))) ?: '—';
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return implode(', ', array_filter(array_map('strval', $decoded))) ?: '—';
            }
        }

        return (string) $value;
    }

    /**
     * Get ID image URL - checks User model first, then UserValidation model
     */
    private function getIdImageUrl(?string $userImagePath, ?string $validationImagePath): string
    {
        $path = $userImagePath ?: $validationImagePath;

        if (!$path) {
            return '';
        }

        // If it's already a full URL, return it
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Clean up the path and use custom serve route
        $cleanPath = ltrim($path, '/');

        return route('id.card.serve', ['path' => $cleanPath]);
    }

    /**
     * Check if this user has blocked another user
     */
    public function hasBlocked(int $userId): bool
    {
        return UserBlock::where('blocker_id', $this->id)
            ->where('blocked_id', $userId)
            ->active()
            ->exists();
    }

    /**
     * Check if this user is blocked by another user
     */
    public function isBlockedBy(int $userId): bool
    {
        return UserBlock::where('blocker_id', $userId)
            ->where('blocked_id', $this->id)
            ->active()
            ->exists();
    }

    /**
     * Get all users blocked by this user
     */
    public function blockedUsers(): HasMany
    {
        return $this->hasMany(UserBlock::class, 'blocker_id');
    }
}
