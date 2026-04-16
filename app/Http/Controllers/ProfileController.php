<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserValidation;
use App\Models\RoommateProfile;
use App\Models\Department;
use App\Models\Course;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfileDetailsUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show(User $user)
    {
        // Load the user's relationships first
        $user->load('roommateProfile', 'preferences', 'userValidation');
        
        // Calculate completion percentage
        $completionPercentage = $this->calculateCompletionPercentage($user);
        
        // Check if user has already been redirected from profile completion
        $alreadyRedirected = $user->profile_completed_redirect ?? false;
        
        // TEMPORARILY DISABLED: Break redirect loop
        // SIMPLIFIED: If profile is 100% complete and hasn't been redirected, redirect to dashboard
        if ($completionPercentage >= 100 && !$alreadyRedirected && false) {
            // Mark user as redirected to prevent future loops
            $user->update(['profile_completed_redirect' => true]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Your profile is complete! Welcome to your dashboard.')
                ->with('from_profile_complete', true);
        }
        
        // Determine back URL based on referer
        $referer = request()->headers->get('referer');
        $backUrl = route('matches.index');
        $backLabel = 'Back to Matches';

        if ($referer && str_contains($referer, 'roommates')) {
            $backUrl = route('roommates.index');
            $backLabel = 'Back to Roommates';
        }

        // Check if there is an accepted match
        $isMatch = false;
        if (auth()->check()) {
            $isMatch = \App\Models\RoommateMatch::where(function($query) use ($user) {
                $query->where('user_id', auth()->id())
                      ->where('matched_user_id', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('matched_user_id', auth()->id());
            })->where('status', 'accepted')->exists();
        }

        return view('profile.show', [
            'user' => $user,
            'profile' => $user->roommateProfile,
            'preferences' => $user->preferences,
            'isMatch' => $isMatch,
            'completionPercentage' => $completionPercentage,
            'backUrl' => $backUrl,
            'backLabel' => $backLabel
        ]);
    }
    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        
        // Set session flag to indicate user is working on profile
        session(['from_profile_edit' => true]);
        
        // Calculate completion percentage
        $completionPercentage = $this->calculateCompletionPercentage($user);
        
        // Check if user has already been redirected from profile completion
        $alreadyRedirected = $user->profile_completed_redirect ?? false;
        
        // DISABLED: Auto-redirect causing loop - use manual button instead
        // SAFE: If profile is 100% complete and hasn't been redirected, redirect to dashboard
        /*
        if ($completionPercentage >= 100 && !$alreadyRedirected) {
            // Mark user as redirected to prevent future loops
            $user->update(['profile_completed_redirect' => true]);
            
            \Log::info('Profile 100% complete, redirecting to dashboard', [
                'user_id' => $user->id,
                'completion_percentage' => $completionPercentage,
                'already_redirected' => $alreadyRedirected
            ]);
            
            $redirect = redirect()->route('dashboard')->with('success', 'Your profile is complete! Welcome to your dashboard.');
            
            // Add security headers to prevent browser blocking
            return $redirect->withHeaders([
                'X-Frame-Options' => 'SAMEORIGIN',
                'Content-Security-Policy' => "frame-ancestors 'self'",
                'Referrer-Policy' => 'no-referrer-when-downgrade'
            ]);
        } elseif ($completionPercentage < 100 && $alreadyRedirected) {
            // Clear redirect flag if profile is no longer complete
            $user->update(['profile_completed_redirect' => false]);
            
            \Log::info('Profile incomplete, clearing redirect flag', [
                'user_id' => $user->id,
                'completion_percentage' => $completionPercentage,
                'already_redirected' => $alreadyRedirected
            ]);
        }
        */
        
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        $initialDepartmentId = null;
        if (! empty($user->course)) {
            $matchedCourse = Course::where('name', $user->course)->where('is_active', true)->first();
            if ($matchedCourse) {
                $initialDepartmentId = $matchedCourse->department_id;
            }
        }
        if ($initialDepartmentId === null && ! empty($user->department)) {
            $initialDepartmentId = Department::where('name', $user->department)->where('is_active', true)->value('id');
        }

        $dagupanUniversities = [
            'Pangasinan State University – Dagupan Campus',
            'Lyceum-Northwestern University',
            'University of Luzon',
            'Universidad de Dagupan',
            'City College of Dagupan',
            'Colegio de Dagupan',
            'Data Center College of the Philippines – Dagupan',
            'AMA Computer College Dagupan',
            'STI College Dagupan',
            'International School of Asia and the Pacific (Dagupan)',
            'PIMSAT Colleges Dagupan',
            'AIE College (Dagupan)',
        ];

        return view('profile.edit', [
            'user' => $user,
            'profile' => $user->roommateProfile ?? new \App\Models\UserPreference(),
            'preferences' => $user->preferences ?? new \App\Models\UserPreference(),
            'completionPercentage' => $completionPercentage,
            'departments' => $departments,
            'initialDepartmentId' => $initialDepartmentId,
            'dagupanUniversities' => $dagupanUniversities,
        ]);
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateCompletionPercentage($user)
    {
        $totalFields = 0;
        $completedFields = 0;
        $fieldStatus = [];
        
        // Basic Information fields (7 fields - added bio)
        $basicFields = ['first_name', 'last_name', 'email', 'phone', 'gender', 'date_of_birth', 'bio'];
        $totalFields += count($basicFields);
        foreach ($basicFields as $field) {
            $isComplete = !empty($user->$field);
            if ($isComplete) {
                $completedFields++;
            }
            $fieldStatus['basic_' . $field] = $isComplete;
        }
        
        // Location Information (1 field) — user.location or roommate profile
        $totalFields++;
        $locationComplete = !empty($user->location)
            || !empty(optional($user->roommateProfile)->apartment_location);
        if ($locationComplete) {
            $completedFields++;
        }
        $fieldStatus['location'] = $locationComplete;
        
        // Education Information (3 fields)
        $educationFields = ['university', 'course', 'year_level'];
        $totalFields += count($educationFields);
        foreach ($educationFields as $field) {
            $isComplete = !empty($user->$field);
            if ($isComplete) {
                $completedFields++;
            }
            $fieldStatus['education_' . $field] = $isComplete;
        }
        
        // Lifestyle Preferences (4 fields)
        $lifestyleFields = ['cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance'];
        $totalFields += count($lifestyleFields);
        if ($user->roommateProfile) {
            foreach ($lifestyleFields as $field) {
                $isComplete = !empty($user->roommateProfile->$field);
                if ($isComplete) {
                    $completedFields++;
                }
                $fieldStatus['lifestyle_' . $field] = $isComplete;
            }
        } else {
            foreach ($lifestyleFields as $field) {
                $fieldStatus['lifestyle_' . $field] = false;
            }
        }
        
        // Budget Information (2 fields)
        $totalFields += 2;
        $budgetComplete = !empty($user->budget_min) && !empty($user->budget_max);
        if ($budgetComplete) {
            $completedFields += 2;
        }
        $fieldStatus['budget_min'] = !empty($user->budget_min);
        $fieldStatus['budget_max'] = !empty($user->budget_max);
        
        // Hobbies (1 field) - Make this truly optional
        // Only count if user has actually entered hobbies, don't penalize if empty
        $hasHobbies = !empty($user->hobbies) || (!empty($user->roommateProfile->hobbies));
        if ($hasHobbies) {
            $totalFields++;
            $completedFields++;
            $fieldStatus['hobbies'] = true;
        } else {
            // Don't count hobbies in total if user hasn't entered any
            $fieldStatus['hobbies'] = 'not_applicable';
        }
        
        // ID Verification (1 field) — both sides uploaded and pending or approved
        $totalFields++;
        $isVerified = $user->isVerified();
        $hasIdSubmission = !empty($user->id_card_front)
            && !empty($user->id_card_back)
            && in_array($user->verification_status ?? '', ['pending', 'approved'], true);
        if ($isVerified || $hasIdSubmission) {
            $completedFields++;
        }
        $fieldStatus['id_verified'] = $isVerified;
        $fieldStatus['id_submitted'] = $hasIdSubmission;
        $fieldStatus['verification_status'] = $user->verification_status;
        
        $percentage = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
        
        // Debug logging
        \Log::info('Profile Completion Calculation', [
            'user_id' => $user->id,
            'total_fields' => $totalFields,
            'completed_fields' => $completedFields,
            'percentage' => $percentage,
            'field_status' => $fieldStatus
        ]);
        
        return $percentage;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's basic profile information.
     */
    public function updateProfileInformation(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['required', 'string', 'max:20'],
                'location' => ['required', 'string', 'max:255'],
            ]);

            $user->update($validated);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile information updated successfully!',
                    'user' => $user->fresh(),
                    'debug_info' => [
                        'user_id' => $user->id,
                        'updated_fields' => array_keys($validated),
                        'timestamp' => now()->toISOString()
                    ]
                ]);
            }

            return redirect()->route('profile.show')
                ->with('status', 'profile-updated')
                ->with('success', 'Profile information updated successfully!')
                ->with('profile_just_completed', $user->isProfileComplete() && $user->isVerified());

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating profile information: ' . $e->getMessage(),
                    'debug_info' => [
                        'error' => $e->getMessage(),
                        'user_id' => $request->user()->id,
                        'timestamp' => now()->toISOString()
                    ]
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error updating profile information: ' . $e->getMessage());
        }
    }

    /**
     * Clear profile completion session flag.
     */
    public function clearCompletionFlag(Request $request)
    {
        session()->forget('profile_just_completed');
        return response()->json(['success' => true]);
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max
            ]);

            $user = $request->user();

            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Store new avatar
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $extension;
            $path = $file->storeAs('avatars', $filename, 'public');

            // Store just the filename in the database
            $user->update(['avatar' => $filename]);

            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($user);

            // If profile is 100% complete, redirect to dashboard
            if ($completionPercentage >= 100) {
                return response()->json([
                    'success' => true,
                    'message' => 'Avatar updated successfully! Profile is now complete.',
                    'completion_percentage' => $completionPercentage,
                    'redirect_to_dashboard' => true,
                    'dashboard_url' => route('dashboard'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'completion_percentage' => $completionPercentage,
                'avatar_url' => asset('storage/avatars/' . $filename),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update user profile photo (alias for updateAvatar).
     */
    public function updatePhoto(Request $request)
    {
        return $this->updateAvatar($request);
    }

    /**
     * Remove user avatar.
     */
    public function removeAvatar(Request $request)
    {
        try {
            $user = $request->user();

            // Delete avatar file if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar removed successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's basic information.
     */
    public function updateBasicInformation(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['required', 'string', 'max:20'],
                'location' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'in:male,female,other,prefer_not_to_say'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'bio' => ['nullable', 'string', 'max:1000'],
                'sleep_pattern' => ['nullable', 'string', 'in:early_bird,night_owl,flexible'], // Add sleep_pattern validation
                'cleanliness_level' => ['nullable', 'string', 'in:very_messy,somewhat_messy,average,somewhat_clean,very_clean'], // Add cleanliness validation
                'study_habit' => ['nullable', 'string', 'in:intense,moderate,social,quiet'], // Add study_habit validation
                'noise_tolerance' => ['nullable', 'string', 'in:quiet,moderate,loud'], // Add noise_tolerance validation
                'budget_min' => ['nullable', 'numeric', 'min:0'], // Add budget validation
                'budget_max' => ['nullable', 'numeric', 'gt:budget_min'], // Add budget validation
                'looking_for_roommate' => ['nullable', 'boolean'], // Add looking_for_roommate validation
            ]);

            // Calculate age from date_of_birth
            $userData = $validated;
            if (!empty($validated['date_of_birth'])) {
                $userData['age'] = \Carbon\Carbon::parse($validated['date_of_birth'])->age;
            }

            $user->update($userData);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => ($validated['first_name'] ?? $user->first_name) . ' ' . ($validated['last_name'] ?? $user->last_name),
                    'age' => $userData['age'] ?? 18,
                    'gender' => $validated['gender'] ?? 'prefer_not_to_say',
                    'bio' => $validated['bio'] ?? null,
                    'apartment_location' => $validated['location'],
                    'cleanliness_level' => 'average',
                    'noise_level' => 'moderate',
                    'schedule' => 'flexible',
                ]
            );

            $user->refresh();
            
            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($user);

            // If profile is 100% complete, redirect to dashboard
            if ($completionPercentage >= 100) {
                return response()->json([
                    'success' => true,
                    'message' => 'Basic information updated successfully! Profile is now complete.',
                    'completion_percentage' => $completionPercentage,
                    'redirect_to_dashboard' => true,
                    'dashboard_url' => route('dashboard'),
                    'debug_info' => [
                        'user_id' => $user->id,
                        'updated_fields' => array_keys($validated),
                        'timestamp' => now()->toISOString(),
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Basic information updated successfully!',
                'completion_percentage' => $completionPercentage,
                'debug_info' => [
                    'user_id' => $user->id,
                    'updated_fields' => array_keys($validated),
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update user's location information.
     */
    public function updateLocationInformation(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'location' => ['required', 'string', 'max:255'],
                'move_in_date' => ['nullable', 'date', 'after_or_equal:today'],
            ]);

            $user->update([
                'location' => $validated['location'],
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $user->first_name . ' ' . $user->last_name,
                    'age' => $user->age ?? 18,
                    'gender' => $user->gender ?? 'prefer_not_to_say',
                    'apartment_location' => $validated['location'],
                    'move_in_date' => $validated['move_in_date'] ?? null,
                    'cleanliness_level' => 'average',
                    'noise_level' => 'moderate',
                    'schedule' => 'flexible',
                ]
            );

            $user->refresh();

            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($user);

            $updatedKeys = ['location', 'apartment_location'];
            if (array_key_exists('move_in_date', $validated)) {
                $updatedKeys[] = 'move_in_date';
            }

            $debugInfo = [
                'user_id' => $user->id,
                'updated_fields' => $updatedKeys,
                'timestamp' => now()->toISOString(),
            ];

            // If profile is 100% complete, redirect to dashboard
            if ($completionPercentage >= 100) {
                return response()->json([
                    'success' => true,
                    'message' => 'Location information updated successfully! Profile is now complete.',
                    'completion_percentage' => $completionPercentage,
                    'redirect_to_dashboard' => true,
                    'dashboard_url' => route('dashboard'),
                    'debug_info' => $debugInfo,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Location information updated successfully!',
                'completion_percentage' => $completionPercentage,
                'debug_info' => $debugInfo,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update user's education information.
     */
    public function updateEducation(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'university' => ['required', 'string', 'max:255'],
                'course' => ['required', 'string', 'max:255'],
                'year_level' => ['required', 'string', 'max:50'],
                'department_id' => ['nullable', 'integer', 'min:0'],
            ]);

            $departmentName = null;
            if (! empty($validated['department_id'])) {
                $departmentName = Department::whereKey($validated['department_id'])->value('name');
            }

            $user->update([
                'university' => $validated['university'],
                'course' => $validated['course'],
                'year_level' => $validated['year_level'],
                'department' => $departmentName,
            ]);
            
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $user->first_name . ' ' . $user->last_name,
                    'age' => $user->age ?? 18,
                    'gender' => $user->gender ?? 'prefer_not_to_say',
                    'university' => $validated['university'],
                    'major' => $validated['course'],
                    'cleanliness_level' => 'average',
                    'noise_level' => 'moderate',
                    'schedule' => 'flexible',
                ]
            );

            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($user);

            $educationDebug = [
                'user_id' => $user->id,
                'updated_fields' => ['university', 'course', 'year_level', 'department'],
                'timestamp' => now()->toISOString(),
            ];

            // If profile is 100% complete, redirect to dashboard
            if ($completionPercentage >= 100) {
                return response()->json([
                    'success' => true,
                    'message' => 'Education information updated successfully! Profile is now complete.',
                    'completion_percentage' => $completionPercentage,
                    'redirect_to_dashboard' => true,
                    'dashboard_url' => route('dashboard'),
                    'debug_info' => $educationDebug,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Education information updated successfully!',
                'completion_percentage' => $completionPercentage,
                'debug_info' => $educationDebug,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function updateLifestyle(Request $request)
    {
        try {
            $user = $request->user();
            
            // DEBUG: Log request data
            \Log::info('updateLifestyle called', [
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'sleep_pattern_value' => $request->input('sleep_pattern'),
            ]);
            
            $validated = $request->validate([
                'cleanliness_level' => ['required', 'string', 'in:very_messy,somewhat_messy,average,somewhat_clean,very_clean'],
                'sleep_pattern' => ['required', 'string', 'in:early_bird,night_owl,flexible'],
                'study_habit' => ['required', 'string', 'in:intense,moderate,social,quiet'],
                'noise_tolerance' => ['required', 'string', 'in:quiet,moderate,loud'],
                'smoking' => ['nullable', 'string', 'in:never,sometimes,regularly,only_outside'],
                'pets' => ['nullable', 'string', 'in:none,cats_ok,dogs_ok,all_pets_ok,no_pets'],
                'overnight_visitors' => ['nullable', 'string', 'in:not_allowed,with_notice,anytime'],
                'schedule' => ['nullable', 'string', 'in:morning,evening,night_shift,irregular'],
                'budget_min' => ['required', 'numeric', 'min:0'],
                'budget_max' => ['required', 'numeric', 'gt:budget_min'],
                'hobbies' => ['nullable', 'array'],
                'lifestyle_tags' => ['nullable', 'array'],
            ]);
            
            // DEBUG: Log validation result
            \Log::info('Validation passed', [
                'validated_data' => $validated,
                'sleep_pattern_validated' => $validated['sleep_pattern'] ?? 'NULL',
            ]);

            $hobbies = [];
            if ($request->has('hobbies') && is_array($request->hobbies)) {
                foreach ($request->hobbies as $hobbyStr) {
                    $pieces = array_filter(array_map('trim', explode(',', $hobbyStr)));
                    $hobbies = array_merge($hobbies, $pieces);
                }
            }
            
            $tags = [];
            if ($request->has('lifestyle_tags') && is_array($request->lifestyle_tags)) {
                foreach ($request->lifestyle_tags as $tagStr) {
                    $pieces = array_filter(array_map('trim', explode(',', $tagStr)));
                    $tags = array_merge($tags, $pieces);
                }
            }

            // Update user model with fields that belong to it
            $user->update([
                'budget_min' => $validated['budget_min'],
                'budget_max' => $validated['budget_max'],
                'hobbies' => json_encode($hobbies),
                'lifestyle_tags' => json_encode($tags),
            ]);
            
            // Update profile with lifestyle fields
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $user->first_name . ' ' . $user->last_name,
                    'age' => $user->age ?? 18,
                    'cleanliness_level' => $validated['cleanliness_level'],
                    'sleep_pattern' => $validated['sleep_pattern'],
                    'study_habit' => $validated['study_habit'],
                    'noise_tolerance' => $validated['noise_tolerance'],
                    'smoking_allowed' => ($validated['smoking'] ?? '') !== 'never',
                    'pets_allowed' => ($validated['pets'] ?? '') !== 'none',
                    'schedule' => $validated['schedule'] ?? 'flexible',
                    'budget_min' => $validated['budget_min'],
                    'budget_max' => $validated['budget_max'],
                ]
            );

            // Sync with Preference model for matching scores
            $user->preference()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cleanliness_level' => $validated['cleanliness_level'],
                    'sleep_pattern' => $validated['sleep_pattern'],
                    'study_habit' => $validated['study_habit'],
                    'noise_tolerance' => $validated['noise_tolerance'],
                    'smoking' => $validated['smoking'] ?? null,
                    'pets' => $validated['pets'] ?? null,
                    'overnight_visitors' => $validated['overnight_visitors'] ?? null,
                    'schedule' => $validated['schedule'] ?? null,
                    'min_budget' => $validated['budget_min'],
                    'max_budget' => $validated['budget_max'],
                ]
            );

            // Calculate completion percentage
            $completionPercentage = $this->calculateCompletionPercentage($user);

            // If profile is 100% complete, redirect to dashboard
            if ($completionPercentage >= 100) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lifestyle preferences updated successfully! Profile is now complete.',
                    'completion_percentage' => $completionPercentage,
                    'redirect_to_dashboard' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lifestyle preferences updated successfully!',
                'completion_percentage' => $completionPercentage
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the user's profile details.
     */
    public function updateDetails(ProfileDetailsUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();
            
            // DEBUG: Log form submission
            \Log::info('ProfileController::updateDetails called', [
                'user_id' => $user->id,
                'form_section' => $request->input('form_section'),
                'validated_data' => $validated,
                'all_request_data' => $request->all()
            ]);

            // Handle form section - only process personal info for this form
            if ($request->input('form_section') === 'personal_information') {
                // Handle location in roommate profile
                $location = $validated['location'] ?? null;
                if ($location === 'other' && !empty($validated['custom_location'])) {
                    $location = $validated['custom_location'];
                }
                
                // Update only personal information fields including location
                $personalData = [
                    'first_name' => $validated['first_name'] ?? null,
                    'last_name' => $validated['last_name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                ];
                
                // Update roommate profile with required fields
                $profileData = [
                    'display_name' => ($validated['first_name'] ?? $user->first_name) . ' ' . ($validated['last_name'] ?? $user->last_name),
                    'gender' => $validated['gender'] ?? $user->gender,
                    'apartment_location' => $location,
                    'cleanliness_level' => 'average',
                    'sleep_pattern' => 'flexible',
                    'study_habit' => 'moderate',
                    'noise_tolerance' => 'moderate',
                ];
                
                $user->update($personalData);
                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
                
                // Set default values for missing required fields to mark profile as complete
                if (empty($user->university)) {
                    $user->update(['university' => 'Not specified']);
                }
                if (empty($user->course)) {
                    $user->update(['course' => 'Not specified']);
                }
                if (empty($user->year_level)) {
                    $user->update(['year_level' => 'Not specified']);
                }
                if (empty($user->budget_min)) {
                    $user->update(['budget_min' => 0]);
                }
                if (empty($user->budget_max)) {
                    $user->update(['budget_max' => 0]);
                }
                
                // Calculate completion percentage
                $completionPercentage = $this->calculateCompletionPercentage($user);
                
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Personal information saved successfully!',
                        'debug_info' => [
                            'user_id' => $user->id,
                            'profile_id' => $user->profile ? $user->profile->id : null,
                            'updated_sections' => [
                                'personal_info' => true,
                            ],
                            'profile_complete' => $user->isProfileComplete(),
                            'timestamp' => now()->toISOString(),
                            'required_fields_status' => [
                                'first_name' => !empty($user->first_name),
                                'last_name' => !empty($user->last_name),
                                'email' => !empty($user->email),
                                'phone' => !empty($user->phone),
                                'gender' => !empty($user->gender),
                                'date_of_birth' => !empty($user->date_of_birth),
                                'location' => !empty($user->location),
                                'university' => !empty($user->university),
                                                                'course' => !empty($user->course),
                                'year_level' => !empty($user->year_level),
                                'budget_min' => !empty($user->budget_min),
                                'budget_max' => !empty($user->budget_max),
                            ]
                        ]
                    ]);
                }
                
                return redirect()->route('profile.show')
                    ->with('status', 'profile-updated')
                    ->with('success', 'Personal information saved successfully!')
                    ->with('profile_just_completed', $user->isProfileComplete() && $user->isVerified());
            }
            
            // Handle education information form
            elseif ($request->input('form_section') === 'education_information') {
                // Update education fields
                $educationData = [
                    'university' => $validated['university'] ?? null,
                    'other_university' => $validated['other_university'] ?? null,
                    'course' => $validated['course'] ?? null,
                    'year_level' => $validated['year_level'] ?? null,
                ];
                
                // Update user education information
                $user->update(array_filter($educationData));
                
                // Set default values for missing required fields
                if (empty($user->first_name)) {
                    $user->update(['first_name' => 'Not specified']);
                }
                if (empty($user->last_name)) {
                    $user->update(['last_name' => 'Not specified']);
                }
                if (empty($user->email)) {
                    $user->update(['email' => 'not@specified.com']);
                }
                if (empty($user->phone)) {
                    $user->update(['phone' => 'Not specified']);
                }
                if (empty($user->gender)) {
                    $user->update(['gender' => 'Not specified']);
                }
                if (empty($user->date_of_birth)) {
                    $user->update(['date_of_birth' => '2000-01-01']);
                }
                if (empty($user->location)) {
                    $user->update(['location' => 'Not specified']);
                }
                if (empty($user->budget_min)) {
                    $user->update(['budget_min' => 0]);
                }
                if (empty($user->budget_max)) {
                    $user->update(['budget_max' => 10000]);
                }
                
                // Update or create roommate profile with default values
                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'display_name' => $user->first_name . ' ' . $user->last_name,
                        'age' => $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : 20,
                        'gender' => $user->gender,
                        'cleanliness_level' => 'average',
                        'noise_level' => 'moderate', // Add required field
                        'schedule' => 'flexible', // Add required field
                        'sleep_pattern' => 'flexible',
                        'study_habit' => 'moderate',
                        'noise_tolerance' => 'moderate',
                    ]
                );
                
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Education information updated successfully!',
                        'debug_info' => [
                            'user_id' => $user->id,
                            'profile_id' => $user->profile ? $user->profile->id : null,
                            'updated_sections' => [
                                'education_info' => true,
                            ],
                            'profile_complete' => $user->isProfileComplete(),
                            'timestamp' => now()->toISOString(),
                            'required_fields_status' => [
                                'first_name' => !empty($user->first_name),
                                'last_name' => !empty($user->last_name),
                                'email' => !empty($user->email),
                                'phone' => !empty($user->phone),
                                'gender' => !empty($user->gender),
                                'date_of_birth' => !empty($user->date_of_birth),
                                'location' => !empty($user->location),
                                'university' => !empty($user->university),
                                                                'course' => !empty($user->course),
                                'year_level' => !empty($user->year_level),
                                'budget_min' => !empty($user->budget_min),
                                'budget_max' => !empty($user->budget_max),
                            ]
                        ]
                    ]);
                }
                
                return redirect()->route('profile.show')
                    ->with('status', 'profile-updated')
                    ->with('success', 'Education information updated successfully!')
                    ->with('profile_just_completed', $user->isProfileComplete() && $user->isVerified());
            }

            // Handle university selection
            if ($request->university === 'Other' && $request->has('other_university')) {
                $validated['university'] = $request->other_university;
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                try {
                    // Delete old avatar if it exists
                    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    
                    // Generate a user-friendly filename
                    $file = $request->file('avatar');
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'avatar_' . $user->id . '_' . time() . '.' . $extension;
                    
                    // Store file with new filename
                    $path = $file->storeAs('avatars', $filename, 'public');
                    $validated['avatar'] = $path;
                    
                    // Log successful upload
                    \Log::info('Avatar uploaded successfully', [
                        'user_id' => $user->id,
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $file->getSize()
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Avatar upload failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload avatar: ' . $e->getMessage()
                        ], 500);
                    }
                    
                    return redirect()->back()
                        ->with('error', 'Failed to upload avatar: ' . $e->getMessage());
                }
            }

            // Update user details
            $user->update($validated);

            // Process hobbies and lifestyle_tags
            $hobbies = array_filter($request->input('hobbies', []), function($hobby) {
                return !empty(trim($hobby));
            });
            
            $lifestyleTags = array_filter($request->input('lifestyle_tags', []), function($tag) {
                return !empty(trim($tag));
            });
            
            // Update user details with JSON encoded fields
            $user->update([
                'hobbies' => !empty($hobbies) ? $hobbies : null,
                'lifestyle_tags' => !empty($lifestyleTags) ? $lifestyleTags : null,
            ]);

            // Update or create roommate profile
            // Calculate age from date_of_birth if provided
            $age = null;
            if (!empty($validated['date_of_birth'])) {
                $age = \Carbon\Carbon::parse($validated['date_of_birth'])->age;
            }
            
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'age' => $age,
                    'gender' => $validated['gender'] ?? 'prefer_not_to_say',
                    'bio' => $validated['bio'] ?? null,
                    'university' => $validated['university'] ?? null,
                    'major' => $validated['course'] ?? null,
                    'cleanliness_level' => $validated['cleanliness_level'] ?? 'average',
                    'sleep_pattern' => $validated['sleep_pattern'] ?? 'flexible',
                    'study_habit' => $validated['study_habit'] ?? 'moderate',
                    'noise_tolerance' => $validated['noise_tolerance'] ?? 'moderate',
                    'budget_min' => $validated['budget_min'] ?? null,
                    'budget_max' => $validated['budget_max'] ?? null,
                ]
            );

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile details saved successfully! All sections updated.',
                    'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) . '?t=' . time() : null,
                    'user' => $user->fresh(),
                    'debug_info' => [
                        'user_id' => $user->id,
                        'profile_id' => $user->profile ? $user->profile->id : null,
                        'updated_sections' => [
                            'personal_info' => true,
                            'education_info' => !empty($validated['university']) && !empty($validated['course']),
                            'lifestyle_preferences' => !empty($validated['cleanliness_level']) && !empty($validated['sleep_pattern']),
                            'budget_info' => !empty($validated['budget_min']) && !empty($validated['budget_max']),
                            'hobbies' => !empty($hobbies),
                            'lifestyle_tags' => !empty($lifestyleTags)
                        ],
                        'profile_complete' => $user->isProfileComplete(),
                    ]
                ]);
            }
            
            return redirect()->route('profile.edit')
                ->with('status', 'profile-updated')
                ->with('success', 'Profile details saved successfully! All sections updated.')
                ->with('profile_just_completed', $user->isProfileComplete() && $user->isVerified());
                
        } catch (\Exception $e) {
            \Log::error('ProfileController::updateDetails error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Update user's ID verification.
     */
    public function updateIdVerification(Request $request)
    {
        try {
            $user = $request->user();
            
            // Debug: Log what we're receiving
            \Log::info('ID Verification Request Data:', [
                'all_data' => $request->all(),
                'files' => $request->allFiles(),
                'has_id_card_front' => $request->hasFile('id_card_front'),
                'has_id_card_back' => $request->hasFile('id_card_back'),
                'id_card_front' => $request->file('id_card_front'),
                'id_card_back' => $request->file('id_card_back'),
                'id_type' => $request->input('id_type'),
                'id_number' => $request->input('id_number'),
            ]);
            
            $validated = $request->validate([
                'id_card_front' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
                'id_card_back' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
                'id_type' => ['required', 'string', 'in:passport,driver_license,national_id,student_id,other'],
                'id_number' => ['required', 'string', 'max:255'],
            ]);

            // Handle front ID card upload
            if ($request->hasFile('id_card_front')) {
                $file = $request->file('id_card_front');
                $extension = $file->getClientOriginalExtension();
                $filename = 'id_front_' . $user->id . '_' . time() . '.' . $extension;
                $path = $file->storeAs('id_cards', $filename, 'public');
                
                // Delete old front ID if it exists
                if ($user->id_card_front && Storage::disk('public')->exists($user->id_card_front)) {
                    Storage::disk('public')->delete($user->id_card_front);
                }
                
                $user->id_card_front = $path;
            }

            // Handle back ID card upload
            if ($request->hasFile('id_card_back')) {
                $file = $request->file('id_card_back');
                $extension = $file->getClientOriginalExtension();
                $filename = 'id_back_' . $user->id . '_' . time() . '.' . $extension;
                $path = $file->storeAs('id_cards', $filename, 'public');
                
                // Delete old back ID if it exists
                if ($user->id_card_back && Storage::disk('public')->exists($user->id_card_back)) {
                    Storage::disk('public')->delete($user->id_card_back);
                }
                
                $user->id_card_back = $path;
            }

            $user->verification_status = 'pending';
            $user->save();

            $validationIdType = match ($validated['id_type']) {
                'driver_license' => 'drivers_license',
                default => $validated['id_type'],
            };

            UserValidation::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id_type' => $validationIdType,
                    'id_number' => $validated['id_number'],
                    'id_front_image' => $user->id_card_front,
                    'id_back_image' => $user->id_card_back,
                    'status' => 'pending',
                    'rejection_reason' => null,
                    'verified_at' => null,
                ]
            );

            $user->refresh();
            $completionPercentage = $this->calculateCompletionPercentage($user);

            return response()->json([
                'success' => true,
                'message' => 'ID verification documents uploaded successfully! Your documents are now pending review by our admin team.',
                'verification_status' => 'pending',
                'id_card_front' => $user->id_card_front,
                'id_card_back' => $user->id_card_back,
                'completion_percentage' => $completionPercentage,
                'redirect_to_dashboard' => $completionPercentage >= 100,
                'dashboard_url' => route('dashboard'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            \Log::error('ProfileController::updateIdVerification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading ID verification: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update user's roommate preferences and availability status.
     */
    public function updateRoommatePreferences(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'looking_for_roommate' => ['nullable', 'boolean'],
                'preferred_gender' => ['nullable', 'string', 'in:no_preference,male,female,other'],
                'number_of_roommates' => ['nullable', 'string', 'in:1,2,3,4+,any'],
                'preferred_noise_level' => ['nullable', 'string', 'in:no_preference,very_quiet,quiet,moderate,lively'],
                'smoking_ok' => ['nullable', 'boolean'],
                'pets_ok' => ['nullable', 'boolean'],
                'preferred_location' => ['nullable', 'string', 'max:255'],
            ]);

            // Update looking_for_roommate status on user
            $user->update([
                'looking_for_roommate' => $request->has('looking_for_roommate'),
            ]);

            // Update or create roommate preferences
            $user->roommatePreference()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'preferred_gender' => $validated['preferred_gender'] ?? 'no_preference',
                    'number_of_roommates' => $validated['number_of_roommates'] ?? 'any',
                    'preferred_noise_level' => $validated['preferred_noise_level'] ?? 'no_preference',
                    'smoking_ok' => $request->has('smoking_ok'),
                    'pets_ok' => $request->has('pets_ok'),
                    'preferred_location' => $validated['preferred_location'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Roommate preferences saved successfully!',
                'looking_for_roommate' => $user->looking_for_roommate,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            \Log::error('ProfileController::updateRoommatePreferences error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving roommate preferences: ' . $e->getMessage()
            ]);
        }
    }
}
