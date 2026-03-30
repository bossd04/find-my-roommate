<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    /**
     * Get user compatibility analytics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompatibilityAnalytics()
    {
        $user = Auth::user();
        $userProfile = $user->roommateProfile;
        
        $totalUsers = User::where('id', '!=', $user->id)->count();
        $highlyCompatibleUsers = 0;
        $moderatelyCompatibleUsers = 0;
        $compatibilityScores = [];
        
        if ($userProfile) {
            $allUsers = User::with('roommateProfile')
                ->where('id', '!=', $user->id)
                ->get();
                
            foreach ($allUsers as $otherUser) {
                // Ensure we have proper User model and roommate profile
                if ($otherUser instanceof User && $otherUser->roommateProfile) {
                    try {
                        $score = $this->calculateCompatibilityScore($user, $otherUser);
                        $compatibilityScores[] = $score;
                        
                        if ($score >= 70) {
                            $highlyCompatibleUsers++;
                        } elseif ($score >= 40) {
                            $moderatelyCompatibleUsers++;
                        }
                    } catch (\Exception $e) {
                        // Log error but continue processing
                        \Log::warning('Error calculating compatibility score for user ' . $otherUser->id . ': ' . $e->getMessage());
                        continue;
                    }
                }
            }
        }
        
        $compatibilityRate = $totalUsers > 0 ? round(($highlyCompatibleUsers / $totalUsers) * 100, 1) : 0;
        $averageScore = count($compatibilityScores) > 0 ? round(array_sum($compatibilityScores) / count($compatibilityScores), 1) : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'highly_compatible_users' => $highlyCompatibleUsers,
                'moderately_compatible_users' => $moderatelyCompatibleUsers,
                'low_compatibility_users' => $totalUsers - $highlyCompatibleUsers - $moderatelyCompatibleUsers,
                'compatibility_rate' => $compatibilityRate,
                'average_score' => $averageScore,
                'compatibility_scores' => $compatibilityScores
            ]
        ]);
    }
    
    /**
     * Get user performance analytics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPerformanceAnalytics()
    {
        $user = Auth::user();
        
        $profileViews = ActivityLog::where('description', 'like', '%profile viewed%')
            ->where('subject_type', 'App\Models\User')
            ->where('subject_id', $user->id)
            ->count();
            
        $profileLikes = ActivityLog::where('description', 'like', '%profile liked%')
            ->where('subject_type', 'App\Models\User')
            ->where('subject_id', $user->id)
            ->count();
            
        $messagesSent = ActivityLog::where('description', 'like', '%message sent%')
            ->where('causer_id', $user->id)
            ->count();
            
        $messagesReceived = ActivityLog::where('description', 'like', '%message received%')
            ->where('subject_type', 'App\Models\User')
            ->where('subject_id', $user->id)
            ->count();
        
        // Calculate profile completion
        $profileCompletion = $this->calculateProfileCompletion($user);
        
        // Calculate response rate (simplified)
        $responseRate = $messagesReceived > 0 ? round(($messagesSent / $messagesReceived) * 100, 1) : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'profile_views' => $profileViews,
                'profile_likes' => $profileLikes,
                'messages_sent' => $messagesSent,
                'messages_received' => $messagesReceived,
                'profile_completion' => $profileCompletion,
                'response_rate' => min($responseRate, 100), // Cap at 100%
                'engagement_score' => $this->calculateEngagementScore($profileViews, $profileLikes, $messagesSent)
            ]
        ]);
    }
    
    /**
     * Get compatibility trends over time
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompatibilityTrends()
    {
        $user = Auth::user();
        
        // Get last 30 days of activity
        $trends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $views = ActivityLog::where('description', 'like', '%profile viewed%')
                ->where('subject_type', 'App\Models\User')
                ->where('subject_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
                
            $likes = ActivityLog::where('description', 'like', '%profile liked%')
                ->where('subject_type', 'App\Models\User')
                ->where('subject_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
                
            $trends[] = [
                'date' => $date,
                'views' => $views,
                'likes' => $likes,
                'total_interactions' => $views + $likes
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }
    
    /**
     * Calculate compatibility score between two users
     *
     * @param User $user1
     * @param User $user2
     * @return int
     */
    private function calculateCompatibilityScore($user1, $user2)
    {
        // Ensure both parameters are User models
        if (!$user1 instanceof User || !$user2 instanceof User) {
            \Log::error('Invalid user types in calculateCompatibilityScore', [
                'user1_type' => get_class($user1),
                'user2_type' => get_class($user2),
                'user1_id' => $user1->id ?? 'unknown',
                'user2_id' => $user2->id ?? 'unknown'
            ]);
            return 0;
        }
        
        $score = 0;
        $profile1 = $user1->roommateProfile;
        $profile2 = $user2->roommateProfile;
        
        if (!$profile1 || !$profile2) {
            \Log::debug('Missing roommate profile for compatibility calculation', [
                'user1_id' => $user1->id,
                'user2_id' => $user2->id,
                'profile1_exists' => $profile1 ? true : false,
                'profile2_exists' => $profile2 ? true : false
            ]);
            return 0;
        }
        
        try {
            // Location compatibility (30%)
            if ($profile1->city && $profile2->city) {
                if ($profile1->city === $profile2->city) {
                    $score += 30;
                }
            }
            
            // Budget compatibility (25%)
            if ($profile1->budget_min && $profile1->budget_max && 
                $profile2->budget_min && $profile2->budget_max) {
                $userBudget = [$profile1->budget_min, $profile1->budget_max];
                $otherBudget = [$profile2->budget_min, $profile2->budget_max];
                
                if (max($userBudget[0], $otherBudget[0]) <= min($userBudget[1], $otherBudget[1])) {
                    $score += 25;
                }
            }
            
            // Lifestyle compatibility (25%)
            if ($profile1->cleanliness_level && $profile2->cleanliness_level) {
                if ($profile1->cleanliness_level === $profile2->cleanliness_level) {
                    $score += 10;
                }
            }
            
            if ($profile1->sleep_pattern && $profile2->sleep_pattern) {
                if ($profile1->sleep_pattern === $profile2->sleep_pattern) {
                    $score += 15;
                }
            }
            
            // University compatibility (20%)
            if ($user1->university && $user2->university) {
                if ($user1->university === $user2->university) {
                    $score += 20;
                }
            }
            
            // Ensure score doesn't exceed 100
            $score = min($score, 100);
            
        } catch (\Exception $e) {
            \Log::error('Error in compatibility score calculation', [
                'user1_id' => $user1->id,
                'user2_id' => $user2->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
        
        return $score;
    }
    
    /**
     * Calculate profile completion percentage
     *
     * @param User $user
     * @return int
     */
    private function calculateProfileCompletion($user)
    {
        $profile = $user->roommateProfile;
        $totalFields = 0;
        $completedFields = 0;
        
        // User fields
        $userFields = ['first_name', 'last_name', 'email', 'gender', 'phone', 'university', 'course', 'year_level'];
        foreach ($userFields as $field) {
            $totalFields++;
            if ($user->$field) {
                $completedFields++;
            }
        }
        
        // Profile fields
        if ($profile) {
            $profileFields = ['apartment_location', 'city', 'university', 'major', 'budget_min', 'budget_max', 'cleanliness_level', 'sleep_pattern'];
            foreach ($profileFields as $field) {
                $totalFields++;
                if ($profile->$field) {
                    $completedFields++;
                }
            }
        }
        
        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }
    
    /**
     * Calculate engagement score
     *
     * @param int $views
     * @param int $likes
     * @param int $messages
     * @return int
     */
    private function calculateEngagementScore($views, $likes, $messages)
    {
        $score = 0;
        $score += min($views * 2, 40);  // Max 40 points from views
        $score += min($likes * 5, 30);  // Max 30 points from likes
        $score += min($messages * 3, 30); // Max 30 points from messages
        
        return min($score, 100); // Cap at 100
    }
}
