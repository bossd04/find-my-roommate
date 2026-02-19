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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoommateProfile $roommateProfile)
    {
        //
    }
}
