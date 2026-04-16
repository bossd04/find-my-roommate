<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        event(new Registered($user));

        // Create notifications for existing active listings
        $this->createNotificationsForExistingListings($user);

        Auth::login($user);

        return redirect()->route('profile.edit')
            ->with('success', 'Welcome! Please complete your profile and upload your ID for verification to start using the system.');
    }

    /**
     * Create notifications for new user about existing active listings
     */
    private function createNotificationsForExistingListings(User $user)
    {
        // Get all active listings
        $activeListings = Listing::where('is_available', true)
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhereNull('status');
            })
            ->with(['user', 'landlord'])
            ->latest()
            ->take(5) // Limit to 5 most recent listings to avoid overwhelming new users
            ->get();

        foreach ($activeListings as $listing) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_listing',
                'title' => 'Available Listing! 🏠',
                'message' => "Check out '{$listing->title}' by " . ($listing->user ? $listing->user->full_name : 'Admin') . " in {$listing->location} for ₱" . number_format($listing->price ?? 0),
                'data' => [
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->title,
                    'listing_location' => $listing->location,
                    'listing_price' => $listing->price ?? 0,
                    'property_type' => $listing->property_type ?? 'apartment',
                ],
            ]);
        }
    }
}
