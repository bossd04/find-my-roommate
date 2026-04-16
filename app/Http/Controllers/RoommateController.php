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

        $query = $this->buildBaseQuery($currentUser);
        $query = $this->applySearchFilters($query, $request);

        $roommates = $this->getRoommatesWithCompatibility($query, $currentUser);
        $paginatedResults = $this->paginateResults($roommates, $request);

        return view('roommates.index', [
            'users' => $paginatedResults,
            'filters' => $request->only(['location', 'budget', 'lifestyle', 'schedule']),
            'currentUser' => $currentUser
        ]);
    }

    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Load the user's profile and preferences
        $user->load(['profile', 'preferences']);
        
        // Check if the user has a profile (required for roommates)
        if (!$user->profile) {
            abort(404, 'User profile not found');
        }
        
        // Track profile view interaction
        $compatibility = $currentUser->getCompatibilityWith($user);
        $compatibility->addInteraction('profile_view');
        
        // Calculate compatibility score based on preferences for the roommates detail page
        $score = $currentUser->calculatePreferenceMatchingScore($user);
        $details = $currentUser->getDetailedMatchingPreferences($user);
        $user->compatibility_score = $score;
        $user->matching_preferences = $details;
        $user->interaction_data = [
            'interaction_count' => $compatibility->interaction_count,
            'profile_views' => $compatibility->profile_views,
            'messages_exchanged' => $compatibility->messages_exchanged,
            'preference_matches' => $compatibility->preference_matches,
            'is_fully_compatible' => $compatibility->is_fully_compatible
        ];
        
        // Check match status
        $existingMatch = \App\Models\RoommateMatch::where(function($query) use ($currentUser, $user) {
            $query->where('user_id', $currentUser->id)->where('matched_user_id', $user->id)
                  ->orWhere('user_id', $user->id)->where('matched_user_id', $currentUser->id);
        })->first();

        $user->match_status = $existingMatch?->status;
        $user->match_id = $existingMatch?->id;
        $user->is_mutual_match = $existingMatch?->is_mutual ?? false;
        
        return view('roommates.show', [
            'roommate' => $user,
            'currentUser' => $currentUser
        ]);
    }

    private function buildBaseQuery(User $currentUser): \Illuminate\Database\Eloquent\Builder
    {
        return User::where('id', '!=', $currentUser->id)
            ->whereHas('profile')
            ->with(['profile', 'preferences']);
    }

    private function applySearchFilters(\Illuminate\Database\Eloquent\Builder $query, Request $request): \Illuminate\Database\Eloquent\Builder
    {
        // Apply search
        if ($request->filled('search')) {
            $this->applySearchTerm($query, $request->input('search'));
        }

        // Location-based search
        if ($request->filled('location') && $request->input('location') !== 'All Locations') {
            $this->applyLocationFilter($query, $request->input('location'));
        }

        $filters = $request->only(['location', 'budget', 'lifestyle', 'schedule']);

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

        return $query;
    }

    private function applySearchTerm(\Illuminate\Database\Eloquent\Builder $query, string $searchTerm): void
    {
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('university', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('location', 'LIKE', '%' . $searchTerm . '%')
              ->orWhereHas('profile', function($profileQuery) use ($searchTerm) {
                  $profileQuery->where('apartment_location', 'LIKE', '%' . $searchTerm . '%')
                               ->orWhere('city', 'LIKE', '%' . $searchTerm . '%');
              });
        });
    }

    private function applyLocationFilter(\Illuminate\Database\Eloquent\Builder $query, string $location): void
    {
        $query->where(function($q) use ($location) {
            $q->whereHas('profile', function($profileQuery) use ($location) {
                $profileQuery->where('apartment_location', 'LIKE', '%' . $location . '%')
                            ->orWhere('city', 'LIKE', '%' . $location . '%');
            })
            ->orWhere('location', 'LIKE', '%' . $location . '%');
        });
    }

    private function getRoommatesWithCompatibility(\Illuminate\Database\Eloquent\Builder $query, User $currentUser): \Illuminate\Support\Collection
    {
        return $query->get()->map(function($user) use ($currentUser) {
            $compatibility = $this->calculateCompatibility($currentUser, $user);
            $user->compatibility_score = $compatibility['score'];
            $user->matching_preferences = $compatibility['matching_preferences'];
            $user->interaction_data = $compatibility['interaction_data'];
            $user->match_status = null;
            $user->match_id = null;
            $user->is_mutual_match = false;

            $existingMatch = \App\Models\RoommateMatch::where(function($query) use ($currentUser, $user) {
                $query->where('user_id', $currentUser->id)->where('matched_user_id', $user->id)
                      ->orWhere('user_id', $user->id)->where('matched_user_id', $currentUser->id);
            })->first();

            if ($existingMatch) {
                $user->match_status = $existingMatch->status;
                $user->match_id = $existingMatch->id;
                $user->is_mutual_match = $existingMatch->is_mutual ?? false;
            }

            return $user;
        })->filter(function($user) {
            return $user->compatibility_score >= 0;
        })->sortByDesc('compatibility_score');
    }

    private function paginateResults(\Illuminate\Support\Collection $roommates, Request $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $perPage = 9;
        $currentPage = $request->input('page', 1);
        $roommatesCollection = $roommates->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $roommatesCollection->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $roommatesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
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
        // Use the preference matching score for the roommates page
        $score = $currentUser->calculatePreferenceMatchingScore($potentialRoommate);
        
        // Use the detailed preferences for display
        $details = $currentUser->getDetailedMatchingPreferences($potentialRoommate);
        
        // Get interaction data separately if needed, but compatibility_score on this page is preference-based
        $compatibilityRecord = $currentUser->getCompatibilityWith($potentialRoommate);
        
        return [
            'score' => $score,
            'matching_preferences' => $details,
            'interaction_data' => [
                'interaction_count' => $compatibilityRecord->interaction_count,
                'profile_views' => $compatibilityRecord->profile_views,
                'messages_exchanged' => $compatibilityRecord->messages_exchanged,
                'preference_matches' => $compatibilityRecord->preference_matches,
                'is_fully_compatible' => $compatibilityRecord->is_fully_compatible,
            ]
        ];
    }

    /**
     * Search roommates by location for API/map search
     */
    public function searchByLocation(Request $request)
    {
        $location = $request->input('location');
        
        if (!$location) {
            return response()->json([
                'roommates' => [],
                'message' => 'No location provided'
            ]);
        }

        $currentUser = Auth::user();
        
        // Build query to find users in the specified location
        $query = User::where('id', '!=', $currentUser ? $currentUser->id : 0)
            ->whereHas('profile')
            ->with(['profile', 'preferences'])
            ->where(function($q) use ($location) {
                $q->whereHas('profile', function($profileQuery) use ($location) {
                    $profileQuery->where('apartment_location', 'LIKE', '%' . $location . '%')
                                ->orWhere('city', 'LIKE', '%' . $location . '%');
                })
                ->orWhere('location', 'LIKE', '%' . $location . '%')
                ->orWhere('university', 'LIKE', '%' . $location . '%');
            });
        
        // Get users and format for response
        $roommates = $query->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url ?? $user->profile_photo_url ?? null,
                'university' => $user->university,
                'location' => $user->location ?? optional($user->profile)->apartment_location ?? optional($user->profile)->city ?? 'Pangasinan',
                'bio' => optional($user->profile)->bio ?? 'Looking for a roommate!',
                'budget' => optional($user->profile)->budget_max ?? null,
                'gender' => $user->gender,
                'age' => $user->age ?? null,
            ];
        });
        
        return response()->json([
            'roommates' => $roommates,
            'count' => $roommates->count(),
            'location' => $location
        ]);
    }
}
