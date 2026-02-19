<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Show the admin profile page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('admin.profile.show');
    }

    /**
     * Update the admin's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $user = $request->user();
            
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Store new photo with a unique filename
            $file = $request->file('profile_photo');
            $filename = 'profile-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            // Update user's profile photo path
            $user->profile_photo_path = $path;
            $user->save();

            // Generate the full URL to the stored image
            $photoUrl = asset('storage/' . $path) . '?v=' . time();

            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully',
                'path' => $path,
                'photo_url' => $photoUrl
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Profile photo upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile photo. ' . $e->getMessage()
            ], 500);
        }
    }
}
