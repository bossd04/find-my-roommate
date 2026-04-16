<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Skip check for admin routes, profile edit routes, validation routes, and logout
        if ($request->is('admin*') || $request->routeIs('profile.*') || $request->routeIs('validation.*') || $request->routeIs('logout')) {
            return $next($request);
        }
        
        // Check if user profile is complete
        if ($user && !$user->isProfileComplete()) {
            
            // Check specific requirements to give appropriate messages
            $requiredFields = [
                'first_name', 'last_name', 'email', 'phone', 'gender', 
                'date_of_birth', 'location', 'university', 'course', 'year_level'
            ];
            
            $missingBasicFields = false;
            foreach ($requiredFields as $field) {
                if (empty($user->$field)) {
                    $missingBasicFields = true;
                    break;
                }
            }
            
            // Check if roommate profile is complete
            $missingProfileFields = false;
            if (!$user->profile) {
                $missingProfileFields = true;
            } else {
                $requiredProfileFields = ['cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance'];
                foreach ($requiredProfileFields as $field) {
                    if (empty($user->profile->$field)) {
                        $missingProfileFields = true;
                        break;
                    }
                }
            }
            
            // Check if budget is set
            $missingBudget = empty($user->budget_min) || empty($user->budget_max);
            
            // ID validation: require a record or both sides uploaded on the user (profile form flow)
            $idValidation = $user->userValidation;
            $hasIdOnUser = !empty($user->id_card_front) && !empty($user->id_card_back);
            $missingIdValidation = !$idValidation && !$hasIdOnUser;
            $pendingIdValidation = $idValidation && $idValidation->status === 'pending';
            $rejectedIdValidation = $idValidation && $idValidation->status === 'rejected';
            
            // Provide specific redirect messages based on what's missing
            if ($missingBasicFields || $missingProfileFields || $missingBudget) {
                return redirect()->route('profile.edit')
                    ->with('warning', 'Please complete your profile information before accessing this page.');
            } elseif ($missingIdValidation) {
                return redirect()->route('validation.create')
                    ->with('warning', 'Please upload your ID for verification before accessing this page.');
            } elseif ($pendingIdValidation) {
                return redirect()->route('profile.show', $user->id)
                    ->with('info', 'Your ID verification is pending approval. Please wait for admin review.');
            } elseif ($rejectedIdValidation) {
                return redirect()->route('validation.create')
                    ->with('error', 'Your ID verification was rejected. Please upload a valid ID for verification.');
            }
        }
        
        return $next($request);
    }
}
