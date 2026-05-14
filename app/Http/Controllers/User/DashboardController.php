<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show user dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();

        // Allow all users to access dashboard regardless of completion percentage
        // Users can complete their profile from the dashboard if needed
        return view('dashboard', compact('user'));
    }

    /**
     * Calculate profile completion percentage.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    private function calculateCompletionPercentage($user)
    {
        // Basic profile fields (required)
        $basicFields = [
            'first_name', 'last_name', 'email', 'phone', 'gender', 
            'date_of_birth', 'location', 'university', 'course', 'year_level'
        ];
        
        $completedBasic = 0;
        foreach ($basicFields as $field) {
            if (!empty($user->$field)) {
                $completedBasic++;
            }
        }
        
        // Profile fields (required)
        $profileFields = [
            'cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance'
        ];
        
        $completedProfile = 0;
        if ($user->roommateProfile) {
            foreach ($profileFields as $field) {
                if (!empty($user->roommateProfile->$field)) {
                    $completedProfile++;
                }
            }
        }
        
        // Budget fields (required)
        $budgetFields = ['budget_min', 'budget_max'];
        $completedBudget = 0;
        foreach ($budgetFields as $field) {
            if (!empty($user->$field)) {
                $completedBudget++;
            }
        }
        
        // ID verification (required)
        $hasIdVerification = $user->verification_status === 'approved' || $user->verification_status === 'pending';
        
        // Calculate total fields and completed fields
        $totalFields = count($basicFields) + count($profileFields) + count($budgetFields) + 1; // +1 for ID verification
        $completedFields = $completedBasic + $completedProfile + $completedBudget + ($hasIdVerification ? 1 : 0);
        
        // Calculate percentage
        $percentage = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
        
        return $percentage;
    }
}
