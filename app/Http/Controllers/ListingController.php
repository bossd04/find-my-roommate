<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::with('user')
            ->where('is_available', true)
            ->latest()
            ->paginate(10);

        return view('listings.index', compact('listings'));
    }
    /**
     * Show the form for creating a new listing.
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created listing in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_type' => 'required|string|in:roommate,room,apartment',
            'location' => 'required|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:' . $request->input('min_price'),
            'description' => 'required|string|max:1000',
        ]);

        // Create the listing
        $listing = new Listing();
        $listing->landlord_id = Auth::id();
        $listing->type = $validated['listing_type'];
        $listing->location = $validated['location'];
        $listing->price = $validated['min_price']; // Using min_price as the base price
        $listing->min_price = $validated['min_price'];
        $listing->max_price = $validated['max_price'];
        $listing->description = $validated['description'];
        $listing->status = 'active';
        $listing->is_available = true; // Make sure to set this as it's used in the active scope
        $listing->save();

        // Redirect back to the create listing page with success message
        return redirect()->route('listings.create')
            ->with('success', 'Your listing has been created successfully!');
    }
}
