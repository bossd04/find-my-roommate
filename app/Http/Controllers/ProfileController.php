<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoommateProfile;
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
        // Load the user's roommate profile and preferences
        $user->load('roommateProfile', 'preferences');
        
        return view('profile.show', [
            'user' => $user,
            'profile' => $user->roommateProfile,
            'preferences' => $user->preferences
        ]);
    }
    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.edit', [
            'user' => $user,
            'profile' => $user->roommateProfile ?? new RoommateProfile()
        ]);
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
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $extension;
            $path = $file->storeAs('avatars', $filename, 'public');

            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('storage/' . $path) . '?t=' . time()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update avatar: ' . $e->getMessage()
            ], 500);
        }
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
     * Update the user's profile details.
     */
    public function updateDetails(ProfileDetailsUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

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
                    
                    // Store the file with the new filename
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
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'age' => $validated['age'] ?? null,
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
                            'education_info' => !empty($validated['university']) && !empty($validated['department']),
                            'lifestyle_preferences' => !empty($validated['cleanliness_level']) && !empty($validated['sleep_pattern']),
                            'budget_info' => !empty($validated['budget_min']) && !empty($validated['budget_max']),
                            'hobbies' => !empty($hobbies),
                            'lifestyle_tags' => !empty($lifestyleTags)
                        ],
                        'profile_complete' => $user->isProfileComplete(),
                        'timestamp' => now()->toISOString()
                    ]
                ]);
            }

            return redirect()->route('profile.show')
                ->with('status', 'profile-updated')
                ->with('success', 'Profile details saved successfully! All sections updated.')
                ->with('profile_just_completed', $user->isProfileComplete() && $user->isVerified());

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
}
