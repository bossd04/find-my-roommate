<?php

namespace App\Http\Controllers;

use App\Models\RoommatePreference;
use Illuminate\Http\Request;

class RoommatePreferenceController extends Controller
{
    /**
     * Display the user's roommate preferences.
     */
    public function index()
    {
        $preferences = auth()->user()->roommatePreference ?? new RoommatePreference();
        return view('preferences.edit', compact('preferences'));
    }

    /**
     * Show the form for editing the user's roommate preferences.
     */
    public function edit(RoommatePreference $preferences)
    {
        // Ensure user can only edit their own preferences
        if ($preferences->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('preferences.edit', compact('preferences'));
    }

    /**
     * Update the user's roommate preferences.
     */
    public function update(Request $request, RoommatePreference $preferences)
    {
        // Ensure user can only update their own preferences
        if ($preferences->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'preferred_gender' => 'nullable|in:no_preference,male,female,other',
            'age_range_min' => 'nullable|integer|min:18|max:120',
            'age_range_max' => 'nullable|integer|min:18|max:120',
            'cleanliness' => 'nullable|in:no_preference,very_messy,somewhat_messy,average,somewhat_clean,very_clean',
            'noise_level' => 'nullable|in:no_preference,very_quiet,quiet,moderate,lively,very_loud',
            'schedule' => 'nullable|in:no_preference,morning_person,night_owl,flexible',
            'smoking_ok' => 'nullable|boolean',
            'pets_ok' => 'nullable|boolean',
            'guests_ok' => 'nullable|boolean',
            'apartment_type' => 'nullable|in:no_preference,apartment,house,condo,townhouse,other',
            'furnished' => 'nullable|in:no_preference,furnished,unfurnished,partially_furnished',
            'sharing_room' => 'nullable|in:no,yes',
            'number_of_roommates' => 'nullable|in:1,2,3,4,no_preference',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'lease_duration' => 'nullable|in:no_preference,1 month,3 months,6 months,1 year,flexible',
            'move_in_date' => 'nullable|date|after_or_equal:today',
            'additional_preferences' => 'nullable|string|max:1000',
        ]);

        // Map form fields to database fields
        $mappedData = [
            'preferred_gender' => $validated['preferred_gender'] ?? null,
            'min_age' => $validated['age_range_min'] ?? null,
            'max_age' => $validated['age_range_max'] ?? null,
            'preferred_cleanliness' => $validated['cleanliness'] ?? null,
            'preferred_noise_level' => $validated['noise_level'] ?? null,
            'preferred_schedule' => $validated['schedule'] ?? null,
            'smoking_ok' => $validated['smoking_ok'] ?? false,
            'pets_ok' => $validated['pets_ok'] ?? false,
            'furnished_preferred' => $this->mapFurnished($validated['furnished'] ?? null),
            'willing_to_share_room' => $validated['sharing_room'] === 'yes',
            'min_budget' => $validated['budget_min'] ?? null,
            'max_budget' => $validated['budget_max'] ?? null,
            'preferred_lease_duration' => $validated['lease_duration'] ?? null,
            'preferred_move_in_date' => $validated['move_in_date'] ?? null,
        ];

        $preferences->update($mappedData);

        return redirect()->route('preferences.index')
            ->with('success', 'Your roommate preferences have been updated successfully!');
    }

    /**
     * Map furnished preference to boolean.
     */
    private function mapFurnished($value)
    {
        return match($value) {
            'furnished' => true,
            'unfurnished' => false,
            'partially_furnished' => true,
            'no_preference' => null,
            default => null,
        };
    }
}
