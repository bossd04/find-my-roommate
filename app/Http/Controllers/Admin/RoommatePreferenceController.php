<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoommatePreference;
use App\Models\User;
use Illuminate\Http\Request;

class RoommatePreferenceController extends Controller
{
    /**
     * Display a listing of all roommate preferences.
     */
    public function index(Request $request)
    {
        $query = RoommatePreference::with('user');

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by preferred gender
        if ($request->filled('gender')) {
            $query->where('preferred_gender', $request->gender);
        }

        // Filter by smoking preference
        if ($request->has('smoking')) {
            $query->where('smoking_ok', $request->boolean('smoking'));
        }

        // Filter by pets preference
        if ($request->has('pets')) {
            $query->where('pets_ok', $request->boolean('pets'));
        }

        $preferences = $query->latest()->paginate(20)->withQueryString();

        return view('admin.preferences.index', compact('preferences'));
    }

    /**
     * Display the specified roommate preference.
     */
    public function show(RoommatePreference $preference)
    {
        $preference->load('user');
        return view('admin.preferences.show', compact('preference'));
    }

    /**
     * Show the form for editing the specified roommate preference.
     */
    public function edit(RoommatePreference $preference)
    {
        $preference->load('user');
        return view('admin.preferences.edit', compact('preference'));
    }

    /**
     * Update the specified roommate preference.
     */
    public function update(Request $request, RoommatePreference $preference)
    {
        $validated = $request->validate([
            'preferred_gender' => 'nullable|in:no_preference,male,female,other',
            'min_age' => 'nullable|integer|min:18|max:120',
            'max_age' => 'nullable|integer|min:18|max:120',
            'preferred_cleanliness' => 'nullable|in:no_preference,very_messy,somewhat_messy,average,somewhat_clean,very_clean',
            'preferred_noise_level' => 'nullable|in:no_preference,quiet,moderate,loud',
            'preferred_schedule' => 'nullable|in:no_preference,morning_person,night_owl,flexible',
            'smoking_ok' => 'nullable|boolean',
            'pets_ok' => 'nullable|boolean',
            'has_apartment_preferred' => 'nullable|boolean',
            'preferred_location' => 'nullable|string|max:255',
            'min_budget' => 'nullable|numeric|min:0',
            'max_budget' => 'nullable|numeric|min:0',
            'preferred_move_in_date' => 'nullable|date',
            'preferred_lease_duration' => 'nullable|string|max:50',
            'willing_to_share_room' => 'nullable|boolean',
            'furnished_preferred' => 'nullable|boolean',
            'utilities_included_preferred' => 'nullable|boolean',
            'preferred_room_type' => 'nullable|string|max:50',
        ]);

        $preference->update($validated);

        return redirect()->route('admin.preferences.index')
            ->with('success', 'Roommate preferences updated successfully!');
    }

    /**
     * Remove the specified roommate preference.
     */
    public function destroy(RoommatePreference $preference)
    {
        $preference->delete();

        return redirect()->route('admin.preferences.index')
            ->with('success', 'Roommate preferences deleted successfully!');
    }
}
