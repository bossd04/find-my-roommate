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
                    'message' => 'Profile updated successfully',
                    'avatar_url' => $user->avatar ? Storage::url($user->avatar) . '?t=' . time() : null,
                    'user' => $user->fresh()
                ]);
            }

            return redirect()->route('profile.show')
                ->with('status', 'profile-updated')
                ->with('success', 'Profile updated successfully!');

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
