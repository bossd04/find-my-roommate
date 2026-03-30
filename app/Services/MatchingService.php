<?php

namespace App\Services;

use App\Models\User;
use App\Models\RoommateProfile;
use App\Models\RoommatePreference;
use Illuminate\Support\Collection;

class MatchingService
{
    /**
     * Calculate compatibility score between two users
     */
    public function calculateCompatibility(User $currentUser, User $potentialRoommate): array
    {
        $score = 0;
        $matchingPreferences = [];
        
        // Check if users have had conversations to boost compatibility
        $conversationBonus = $this->calculateConversationBonus($currentUser, $potentialRoommate);
        
        // Enhanced AI Compatibility Scoring Algorithm
        $currentProfile = $currentUser->profile;
        $potentialProfile = $potentialRoommate->profile;
        $currentPrefs = $currentUser->preference ? $currentUser->preference->toArray() : [];
        $potentialPrefs = $potentialRoommate->preference ? $potentialRoommate->preference->toArray() : [];
        
        // Remove timestamps and IDs from comparison
        $excludeKeys = ['id', 'user_id', 'created_at', 'updated_at'];
        
        // Standardized preference weights for consistent scoring
        $preferenceWeights = [
            'sleep_pattern' => 20,            // Sleep schedule alignment
            'study_habit' => 15,              // Study habits compatibility
            'noise_tolerance' => 15,          // Noise tolerance matching
            'overnight_visitors' => 10,        // Social compatibility
            'smoking' => 10,                   // Health/lifestyle compatibility
            'pets' => 10,                     // Pet preferences
            'schedule' => 20,                 // Daily schedule alignment
            'budget' => 20,                   // Financial compatibility
            'age' => 15,                      // Age compatibility
            'gender' => 10                    // Gender preferences
        ];
        
        $totalWeight = array_sum($preferenceWeights);
        
        // Calculate preference-based compatibility with consistent scoring
        foreach ($currentPrefs as $key => $value) {
            if (in_array($key, $excludeKeys)) continue;
            
            $weight = $preferenceWeights[$key] ?? 0;
            
            if (isset($potentialPrefs[$key])) {
                if (is_array($value) && is_array($potentialPrefs[$key])) {
                    // For array values (like multiple selections)
                    $matching = array_intersect($value, $potentialPrefs[$key]);
                    $totalOptions = array_unique(array_merge($value, $potentialPrefs[$key]));
                    $matchPercent = count($matching) / max(count($totalOptions), 1);
                    $score += $matchPercent * $weight;
                    
                    if ($matchPercent > 0) {
                        $matchingPreferences[$key] = [
                            'your_choice' => $value,
                            'their_choice' => $potentialPrefs[$key],
                            'match' => $matching,
                            'score' => $matchPercent * $weight
                        ];
                    }
                } else if ($value == $potentialPrefs[$key]) {
                    // For simple values
                    $score += $weight;
                    $matchingPreferences[$key] = [
                        'your_choice' => $value,
                        'their_choice' => $potentialPrefs[$key],
                        'match' => true,
                        'score' => $weight
                    ];
                } else {
                    $matchingPreferences[$key] = [
                        'your_choice' => $value,
                        'their_choice' => $potentialPrefs[$key],
                        'match' => false,
                        'score' => 0
                    ];
                }
            }
        }
        
        // Standardized AI Scoring - Additional compatibility factors
        $aiScore = 0;
        $ageDiff = 0;
        $lifestyleBonus = 0;
        $cleanlinessBonus = 0;
        
        // 1. Age Compatibility (15 points)
        if ($currentProfile && $potentialProfile) {
            $ageDiff = abs($currentProfile->age - $potentialProfile->age);
            if ($ageDiff <= 3) {
                $aiScore += 15; // Perfect age match
            } elseif ($ageDiff <= 5) {
                $aiScore += 10; // Good age match
            } elseif ($ageDiff <= 8) {
                $aiScore += 5; // Acceptable age match
            }
        }
        
        // 2. University Match (15 points)
        if ($currentProfile && $potentialProfile) {
            if ($currentProfile->university && $potentialProfile->university) {
                if ($currentProfile->university === $potentialProfile->university) {
                    $aiScore += 15; // Same university
                }
            }
        }
        
        // 3. Budget Compatibility (20 points)
        if ($currentProfile && $potentialProfile) {
            if ($currentProfile->budget_min && $currentProfile->budget_max && 
                $potentialProfile->budget_min && $potentialProfile->budget_max) {
                
                $currentBudgetMid = ($currentProfile->budget_min + $currentProfile->budget_max) / 2;
                $potentialBudgetMid = ($potentialProfile->budget_min + $potentialProfile->budget_max) / 2;
                $budgetDiff = abs($currentBudgetMid - $potentialBudgetMid);
                
                if ($budgetDiff <= 500) {
                    $aiScore += 20; // Very compatible budget
                } elseif ($budgetDiff <= 1500) {
                    $aiScore += 15; // Compatible budget
                } elseif ($budgetDiff <= 3000) {
                    $aiScore += 10; // Somewhat compatible budget
                }
            }
        }
        
        // 4. Lifestyle Harmony Bonus (10 points)
        if (isset($currentPrefs['sleep_pattern']) && isset($potentialPrefs['sleep_pattern'])) {
            $compatiblePatterns = [
                ['morning_person', 'morning_person'],      // Perfect match
                ['night_owl', 'night_owl'],                // Good match
                ['flexible', 'flexible'],                  // Compatible
            ];
            
            foreach ($compatiblePatterns as $compatible) {
                if (in_array($currentPrefs['sleep_pattern'], $compatible) && 
                    in_array($potentialPrefs['sleep_pattern'], $compatible)) {
                    $lifestyleBonus += 10;
                    break;
                }
            }
        }
        
        // 5. Cleanliness Compatibility (10 points)
        if ($currentProfile && $potentialProfile) {
            $cleanlinessLevels = ['very_messy', 'somewhat_messy', 'average', 'somewhat_clean', 'very_clean'];
            $currentLevel = array_search($currentProfile->cleanliness_level, $cleanlinessLevels);
            $potentialLevel = array_search($potentialProfile->cleanliness_level, $cleanlinessLevels);
            
            if ($currentLevel !== false && $potentialLevel !== false) {
                $levelDiff = abs($currentLevel - $potentialLevel);
                if ($levelDiff <= 1) {
                    $cleanlinessBonus += 10; // Very compatible
                } elseif ($levelDiff <= 2) {
                    $cleanlinessBonus += 5; // Compatible
                }
            }
        }
        
        // Add AI bonus scores to main score
        $score += $aiScore + $lifestyleBonus + $cleanlinessBonus + $conversationBonus;
        
        // Calculate final score as percentage with consistent scaling
        $maxPossibleScore = $totalWeight + 85; // 70 is max AI bonus points + 15 for conversation bonus
        $finalScore = min(($score / $maxPossibleScore) * 100, 100);
        
        // Add AI insights to matching preferences
        $aiInsights = [];
        
        // Calculate age difference for AI insights
        $ageDiff = 0;
        if ($currentProfile && $potentialProfile) {
            $ageDiff = abs($currentProfile->age - $potentialProfile->age);
        }
        
        if ($ageDiff <= 3) {
            $aiInsights[] = 'Perfect age match';
        }
        if ($lifestyleBonus >= 10) {
            $aiInsights[] = 'Highly compatible sleep patterns';
        }
        if ($cleanlinessBonus >= 10) {
            $aiInsights[] = 'Compatible cleanliness standards';
        }
        if ($conversationBonus > 0) {
            $aiInsights[] = 'Active communicator - has started conversations';
        }
        
        return [
            'score' => round($finalScore),
            'matching_preferences' => $matchingPreferences,
            'ai_insights' => $aiInsights,
            'ai_bonus_score' => $aiScore + $lifestyleBonus + $cleanlinessBonus,
            'age_compatibility' => $ageDiff <= 5 ? 'high' : ($ageDiff <= 10 ? 'medium' : 'low'),
            'lifestyle_compatibility' => $lifestyleBonus >= 10 ? 'high' : ($lifestyleBonus > 0 ? 'medium' : 'low'),
            'cleanliness_compatibility' => $cleanlinessBonus >= 10 ? 'high' : ($cleanlinessBonus > 0 ? 'medium' : 'low')
        ];
    }
    
    /**
     * Find best matches for a user
     */
    public function findBestMatches(User $currentUser, int $limit = 10): Collection
    {
        $query = User::where('id', '!=', $currentUser->id)
            ->whereHas('profile')
            ->with(['profile', 'preferences']);
        
        $potentialRoommates = $query->get();
        
        // Calculate compatibility scores
        $roommatesWithScores = $potentialRoommates->map(function($user) use ($currentUser) {
            // Ensure we have proper User objects
            if (!$user instanceof User) {
                return null;
            }
            
            $compatibility = $this->calculateCompatibility($currentUser, $user);
            $user->compatibility_score = $compatibility['score'];
            $user->matching_preferences = $compatibility['matching_preferences'];
            $user->ai_insights = $compatibility['ai_insights'];
            $user->ai_bonus_score = $compatibility['ai_bonus_score'];
            $user->age_compatibility = $compatibility['age_compatibility'];
            $user->lifestyle_compatibility = $compatibility['lifestyle_compatibility'];
            $user->cleanliness_compatibility = $compatibility['cleanliness_compatibility'];
            return $user;
        })->filter(function($user) {
            // Only show users with at least 50% compatibility
            return $user && $user->compatibility_score >= 50;
        })->sortByDesc('compatibility_score');
        
        return $roommatesWithScores->take($limit);
    }
    
    /**
     * Get compatibility breakdown for detailed analysis
     */
    public function getCompatibilityBreakdown(User $currentUser, User $potentialRoommate): array
    {
        $compatibility = $this->calculateCompatibility($currentUser, $potentialRoommate);
        
        return [
            'overall_score' => $compatibility['score'],
            'ai_bonus_score' => $compatibility['ai_bonus_score'],
            'matching_preferences' => $compatibility['matching_preferences'],
            'ai_insights' => $compatibility['ai_insights'],
            'compatibility_levels' => [
                'age' => $compatibility['age_compatibility'],
                'lifestyle' => $compatibility['lifestyle_compatibility'],
                'cleanliness' => $compatibility['cleanliness_compatibility']
            ],
            'recommendation' => $this->generateRecommendation($compatibility)
        ];
    }
    
    /**
     * Generate AI-powered recommendation
     */
    private function generateRecommendation(array $compatibility): string
    {
        $score = $compatibility['score'];
        
        if ($score >= 90) {
            return 'Excellent match! You have very high compatibility across all factors.';
        } elseif ($score >= 80) {
            return 'Great match! You share many preferences and lifestyle factors.';
        } elseif ($score >= 70) {
            return 'Good match! You have solid compatibility with some differences that could work well.';
        } elseif ($score >= 60) {
            return 'Fair match. You have some compatibility but may need to discuss differences.';
        } else {
            return 'Low compatibility. You may want to consider if your preferences align well enough.';
        }
    }
    
    /**
     * Calculate conversation-based compatibility bonus
     */
    private function calculateConversationBonus(User $currentUser, User $potentialRoommate): int
    {
        // Check if users have had conversations
        $hasConversed = \App\Models\Message::where(function($query) use ($currentUser, $potentialRoommate) {
            $query->where(function($subQuery) use ($currentUser, $potentialRoommate) {
                $subQuery->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $potentialRoommate->id);
            })->orWhere(function($subQuery) use ($currentUser, $potentialRoommate) {
                $subQuery->where('sender_id', $potentialRoommate->id)
                      ->where('receiver_id', $currentUser->id);
            });
        })->exists();
        
        // Add bonus points for users who have started communicating
        if ($hasConversed) {
            return 15; // 15 point bonus for having conversations
        }
        
        return 0;
    }
    
    /**
     * Analyze matching trends across all users
     */
    public function analyzeMatchingTrends(): array
    {
        $users = User::with(['profile', 'preferences'])->get();
        
        $trends = [
            'most_compatible_departments' => [],
            'common_preferences' => [],
            'average_compatibility_score' => 0,
            'total_matches_analyzed' => 0
        ];
        
        // This would be expanded with more sophisticated analysis
        return $trends;
    }
}
