<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class HomeController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // The auth middleware is now handled by the route definition
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has completed their profile
        if (!$user->profile) {
            return redirect()->route('profiles.create')
                ->with('info', 'Please complete your profile to find roommates.');
        }
        
        // Get potential matches
        $matches = $this->findPotentialMatches($user);
        
        return view('home', [
            'user' => $user,
            'matches' => $matches,
        ]);
    }
    
    /**
     * Find potential roommate matches for the user.
     */
    protected function findPotentialMatches(User $user)
    {
        if (!$user->preferences) {
            return collect();
        }
        
        $preferences = $user->preferences;
        $userProfile = $user->profile;
        
        // Base query to find potential roommates
        $query = User::where('id', '!=', $user->id)
            ->whereHas('profile')
            ->whereHas('preferences')
            ->with(['profile', 'preferences']);
        
        // Filter by gender preference if set
        if ($preferences->preferred_gender !== 'no_preference') {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('gender', $preferences->preferred_gender);
            });
        }
        
        // Filter by age range if set
        if ($preferences->min_age) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('age', '>=', $preferences->min_age);
            });
        }
        
        if ($preferences->max_age) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('age', '<=', $preferences->max_age);
            });
        }
        
        // Filter by smoking preference
        if (!$preferences->smoking_ok) {
            $query->whereHas('profile', function($q) {
                $q->where('smoking_allowed', false);
            });
        }
        
        // Filter by pets preference
        if (!$preferences->pets_ok) {
            $query->whereHas('profile', function($q) {
                $q->where('pets_allowed', false);
            });
        }
        
        // Filter by apartment preference if set
        if (!is_null($preferences->has_apartment_preferred)) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('has_apartment', $preferences->has_apartment_preferred);
            });
        }
        
        // Filter by preferred location if set
        if ($preferences->preferred_location) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('apartment_location', 'like', '%' . $preferences->preferred_location . '%');
            });
        }
        
        // Filter by budget range if set
        if ($preferences->min_budget) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('budget_min', '>=', $preferences->min_budget)
                  ->orWhereNull('budget_min');
            });
        }
        
        if ($preferences->max_budget) {
            $query->whereHas('profile', function($q) use ($preferences) {
                $q->where('budget_max', '<=', $preferences->max_budget)
                  ->orWhereNull('budget_max');
            });
        }
        
        // Get results and calculate match scores
        $potentialMatches = $query->get()->map(function($match) use ($userProfile, $preferences) {
            $matchProfile = $match->profile;
            $matchPreferences = $match->preferences;
            
            // Calculate match score (simple example - can be enhanced)
            $score = 0;
            $totalCriteria = 0;
            
            // Cleanliness match
            if ($matchPreferences->preferred_cleanliness !== 'no_preference') {
                $totalCriteria++;
                if ($matchProfile->cleanliness_level === $preferences->preferred_cleanliness) {
                    $score++;
                }
            }
            
            // Noise level match
            if ($matchPreferences->preferred_noise_level !== 'no_preference') {
                $totalCriteria++;
                if ($matchProfile->noise_level === $preferences->preferred_noise_level) {
                    $score++;
                }
            }
            
            // Schedule match
            if ($matchPreferences->preferred_schedule !== 'no_preference') {
                $totalCriteria++;
                if ($matchProfile->schedule === $preferences->preferred_schedule) {
                    $score++;
                }
            }
            
            // Calculate match percentage
            $matchPercentage = $totalCriteria > 0 ? ($score / $totalCriteria) * 100 : 0;
            
            return [
                'user' => $match,
                'score' => round($matchPercentage),
            ];
        });
        
        // Sort by match score (highest first)
        return $potentialMatches->sortByDesc('score')->values();
    }
    
    /**
     * Show a user's profile.
     */
    public function showProfile(User $user)
    {
        if (!$user->profile) {
            return redirect()->route('home')
                ->with('error', 'Profile not found.');
        }
        
        return view('profiles.show', [
            'user' => $user,
            'profile' => $user->profile,
        ]);
    }
    
    /**
     * Show the about page.
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
}
