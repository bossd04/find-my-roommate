<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommatePreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoommateController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $currentUser->load(['profile', 'preferences']);
        
        $query = User::where('id', '!=', $currentUser->id)
            ->whereHas('profile')
            ->with(['profile', 'preferences']);

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Apply filters
        $filters = $request->only(['location', 'budget', 'lifestyle', 'schedule']);
        
        // Location filter
        if (!empty($filters['location']) && $filters['location'] !== 'All Locations in Dagupan') {
            $query->whereHas('profile', function($q) use ($filters) {
                $q->where('apartment_location', 'LIKE', '%' . $filters['location'] . '%');
            });
        }

        // Budget filter
        if (!empty($filters['budget']) && $filters['budget'] !== 'Any Budget') {
            $this->applyBudgetFilter($query, $filters['budget']);
        }
        
        // Lifestyle filter
        if (!empty($filters['lifestyle'])) {
            $query->whereHas('preferences', function($q) use ($filters) {
                $q->whereIn('lifestyle', (array)$filters['lifestyle']);
            });
        }
        
        // Schedule filter
        if (!empty($filters['schedule'])) {
            $query->whereHas('preferences', function($q) use ($filters) {
                $q->whereIn('schedule', (array)$filters['schedule']);
            });
        }

        // Get filtered users with compatibility scores and detailed matching
        $roommates = $query->get()->map(function($user) use ($currentUser) {
            $compatibility = $this->calculateCompatibility($currentUser, $user);
            $user->compatibility_score = $compatibility['score'];
            $user->matching_preferences = $compatibility['matching_preferences'];
            return $user;
        })->filter(function($user) {
            // Only show users with at least 50% compatibility
            return $user->compatibility_score >= 50;
        })->sortByDesc('compatibility_score');

        // Paginate the results
        $perPage = 9;
        $page = $request->input('page', 1);
        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $roommates->forPage($page, $perPage),
            $roommates->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('roommates.index', [
            'users' => $paginatedResults,
            'filters' => $filters,
            'currentUser' => $currentUser
        ]);
    }
    
    private function applyBudgetFilter($query, $budgetRange)
    {
        $budgetRange = explode(' - ', str_replace(',', '', $budgetRange));
        $minBudget = (float)trim($budgetRange[0]);
        
        if (isset($budgetRange[1])) {
            $maxBudget = (float)trim($budgetRange[1]);
            $query->whereHas('profile', function($q) use ($minBudget, $maxBudget) {
                $q->where('budget_min', '<=', $maxBudget)
                  ->where('budget_max', '>=', $minBudget);
            });
        } else {
            $query->whereHas('profile', function($q) use ($minBudget) {
                $q->where('budget_min', '>=', $minBudget);
            });
        }
    }
    
    private function calculateCompatibility($currentUser, $potentialRoommate)
    {
        $score = 0;
        $totalPossible = 0;
        $matchingPreferences = [];

        // Compare preferences if they exist
        if ($currentUser->preferences && $potentialRoommate->preferences) {
            $currentPrefs = $currentUser->preferences->toArray();
            $potentialPrefs = $potentialRoommate->preferences->toArray();
            
            // Remove timestamps and IDs from comparison
            $excludeKeys = ['id', 'user_id', 'created_at', 'updated_at'];
            $preferenceWeights = [
                'lifestyle' => 25,
                'schedule' => 20,
                'cleanliness' => 20,
                'overnight_visitors' => 10,
                'smoking' => 15,
                'pets' => 10
            ];
            
            $totalWeight = array_sum($preferenceWeights);
            $score = 0;
            
            foreach ($currentPrefs as $key => $value) {
                if (in_array($key, $excludeKeys)) continue;
                
                $weight = $preferenceWeights[$key] ?? 0;
                
                if (isset($potentialPrefs[$key])) {
                    if (is_array($value) && is_array($potentialPrefs[$key])) {
                        // For array values (like multiple selections)
                        $matching = array_intersect($value, $potentialPrefs[$key]);
                        $matchPercent = count($matching) / max(count($value), 1);
                        $score += $matchPercent * $weight;
                        
                        if ($matchPercent > 0) {
                            $matchingPreferences[$key] = [
                                'your_choice' => $value,
                                'their_choice' => $potentialPrefs[$key],
                                'match' => $matching
                            ];
                        }
                    } else if ($value == $potentialPrefs[$key]) {
                        // For simple values
                        $score += $weight;
                        $matchingPreferences[$key] = [
                            'your_choice' => $value,
                            'their_choice' => $potentialPrefs[$key],
                            'match' => true
                        ];
                    } else {
                        $matchingPreferences[$key] = [
                            'your_choice' => $value,
                            'their_choice' => $potentialPrefs[$key],
                            'match' => false
                        ];
                    }
                }
            }
            
            // Calculate final score as percentage of total possible weight
            $score = ($score / $totalWeight) * 100;
        }
        
        return [
            'score' => round($score),
            'matching_preferences' => $matchingPreferences
        ];
    }
}
