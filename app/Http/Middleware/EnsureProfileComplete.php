<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user is verified
        $isVerified = $user->userValidation && $user->userValidation->status === 'approved';
        
        // Check profile completion
        $profile = $user->roommateProfile;
        
        $personalInfoComplete = (
            $user->first_name && $user->last_name && $user->email && 
            $user->gender && $user->phone
        );
        
        $educationInfoComplete = (
            $user->university && $user->course && $user->year_level
        );
        
        $lifestyleInfoComplete = (
            $profile && $profile->cleanliness_level && $profile->sleep_pattern && 
            $profile->study_habit && $profile->noise_tolerance
        );
        
        $profileComplete = $personalInfoComplete && $educationInfoComplete && $lifestyleInfoComplete;
        $canAccessSystem = $isVerified && $profileComplete;
        
        if (!$canAccessSystem) {
            // Redirect to dashboard with message
            return redirect()->route('dashboard')
                ->with('warning', 'Please complete your profile verification and required information before accessing this feature.');
        }
        
        return $next($request);
    }
}
