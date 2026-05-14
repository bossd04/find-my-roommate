<?php

namespace App\Http\Controllers;

use App\Models\RoommateProfile;
use Illuminate\Http\Request;

class RoommateProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Load the user's profile with the preferences relationship
        $profile = $user->profile()->with('preferences')->first();
        
        // If no profile exists, redirect to create profile
        if (!$profile) {
            return redirect()->route('profiles.create');
        }
        
        // Get the preferences from the loaded profile or as a fallback, from the user
        $preferences = $profile->preferences ?? $user->preferences;

        return view('profiles.index', [
            'profile' => $profile,
            'preferences' => $preferences
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RoommateProfile $roommateProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoommateProfile $roommateProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoommateProfile $roommateProfile)
    {
        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:18|max:120',
            'gender' => 'nullable|string|in:male,female,other,prefer_not_to_say',
            'bio' => 'nullable|string|max:1000',
            'facebook_url' => 'nullable|url|max:255',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'cleanliness_level' => 'nullable|string|in:very_messy,somewhat_messy,average,somewhat_clean,very_clean',
            'noise_level' => 'nullable|string|in:quiet,moderate,loud',
            'schedule' => 'nullable|string|in:early_bird,night_owl,flexible',
            'sleep_pattern' => 'nullable|string|in:early_bird,night_owl,flexible',
            'study_habit' => 'nullable|string|in:intense,moderate,social,quiet',
            'noise_tolerance' => 'nullable|string|in:quiet,moderate,loud',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|gte:budget_min',
            'apartment_location' => 'nullable|string|max:255',
        ]);

        $roommateProfile->update($validated);

        // Sync with Preference model for matching scores
        $user = auth()->user();
        if ($user) {
            $user->preference()->updateOrCreate(
                ['user_id' => $user->id],
                array_intersect_key($validated, array_flip([
                    'cleanliness_level', 'sleep_pattern', 'study_habit', 'noise_tolerance', 'budget_min', 'budget_max'
                ]))
            );
        }

        return redirect()->route('profiles.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoommateProfile $roommateProfile)
    {
        //
    }
}
