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

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'bio',
        'date_of_birth',
        'gender',
        'age',
        'course',
        'year_level',
        'department',
        'avatar',
        'occupation',
        'university',
        'move_in_date',
        'budget_min',
        'budget_max',
        'preferred_location',
        'preferred_lease_length',
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
        return $this->name ?: trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!$this->profile_photo_path) {
            return $this->defaultProfilePhotoUrl();
        }
        
        // Generate the URL to the profile photo
        $url = asset('storage/' . $this->profile_photo_path);
        
        // Check if the file exists in the public storage
        $path = storage_path('app/public/' . $this->profile_photo_path);
        if (file_exists($path)) {
            return $url . '?v=' . filemtime($path);
        }
        
        // Fallback to default if file doesn't exist
        return $this->defaultProfilePhotoUrl();
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
                // Check if file exists in storage
                if (Storage::disk('public')->exists($this->avatar)) {
                    return Storage::url($this->avatar);
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
        return $this->date_of_birth ? now()->diffInYears($this->date_of_birth) : null;
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
     * Get the department the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the course the user is taking.
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
     * Calculate compatibility score with another user.
     */
    public function calculateCompatibilityScore(User $otherUser): array
    {
        if (!$this->preference || !$otherUser->preference) {
            return [
                'score' => 0,
                'details' => []
            ];
        }

        $scores = [];
        $totalScore = 0;
        $totalPossibleScore = 0;

        // Cleanliness Level (20% weight)
        if ($this->preference->cleanliness_level === $otherUser->preference->cleanliness_level) {
            $scores['cleanliness'] = ['score' => 20, 'matched' => true];
        } else {
            $scores['cleanliness'] = ['score' => 0, 'matched' => false];
        }
        $totalScore += $scores['cleanliness']['score'];
        $totalPossibleScore += 20;

        // Sleep Pattern (15% weight)
        if ($this->preference->sleep_pattern === $otherUser->preference->sleep_pattern) {
            $scores['sleep_pattern'] = ['score' => 15, 'matched' => true];
        } else {
            $scores['sleep_pattern'] = ['score' => 0, 'matched' => false];
        }
        $totalScore += $scores['sleep_pattern']['score'];
        $totalPossibleScore += 15;

        // Study Habits (10% weight)
        if ($this->preference->study_habit === $otherUser->preference->study_habit) {
            $scores['study_habit'] = ['score' => 10, 'matched' => true];
        } else {
            $scores['study_habit'] = ['score' => 0, 'matched' => false];
        }
        $totalScore += $scores['study_habit']['score'];
        $totalPossibleScore += 10;

        // Noise Tolerance (10% weight)
        if ($this->preference->noise_tolerance === $otherUser->preference->noise_tolerance) {
            $scores['noise_tolerance'] = ['score' => 10, 'matched' => true];
        } else {
            $scores['noise_tolerance'] = ['score' => 0, 'matched' => false];
        }
        $totalScore += $scores['noise_tolerance']['score'];
        $totalPossibleScore += 10;

        // Budget Compatibility (15% weight)
        $budgetOverlap = min($this->budget_max, $otherUser->budget_max) - max($this->budget_min, $otherUser->budget_min);
        if ($budgetOverlap > 0) {
            $scores['budget'] = ['score' => 15, 'matched' => true];
        } else {
            $scores['budget'] = ['score' => 0, 'matched' => false];
        }
        $totalScore += $scores['budget']['score'];
        $totalPossibleScore += 15;

        // Shared Hobbies (10% weight)
        $sharedHobbies = count(array_intersect(
            $this->preference->hobbies ?? [],
            $otherUser->preference->hobbies ?? []
        ));
        $scores['hobbies'] = [
            'score' => min(10, $sharedHobbies * 2), // Max 10 points, 2 points per shared hobby
            'matched' => $sharedHobbies > 0
        ];
        $totalScore += $scores['hobbies']['score'];
        $totalPossibleScore += 10;

        // Calculate final score
        $finalScore = $totalPossibleScore > 0 ? round(($totalScore / $totalPossibleScore) * 100) : 0;

        return [
            'score' => $finalScore,
            'details' => $scores
        ];
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
            'university',
            'department',
            'course',
            'year_level'
        ];

        // Check if all required user fields are filled
        foreach ($requiredFields as $field) {
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

        return true;
    }

    /**
     * Check if user is verified through ID validation.
     */
    public function isVerified(): bool
    {
        return $this->userValidation && $this->userValidation->status === 'approved';
    }
}
