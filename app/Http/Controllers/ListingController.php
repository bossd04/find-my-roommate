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
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'landlord'])
            ->where('is_available', true)
            ->where(function($query) {
                $query->whereNotNull('user_id')
                      ->orWhereNotNull('landlord_id');
            });

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // Apply price range filter
        if ($request->filled('price_range')) {
            $priceRange = $request->input('price_range');
            switch ($priceRange) {
                case '0-3000':
                    $query->where('price', '>=', 0)->where('price', '<=', 3000);
                    break;
                case '3000-5000':
                    $query->where('price', '>=', 3000)->where('price', '<=', 5000);
                    break;
                case '5000-8000':
                    $query->where('price', '>=', 5000)->where('price', '<=', 8000);
                    break;
                case '8000+':
                    $query->where('price', '>=', 8000);
                    break;
            }
        }

        $listings = $query->latest()->paginate(10)->withQueryString();

        return view('listings.index', compact('listings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load(['user', 'landlord']);
        
        // Check if listing is available and active
        if (!$listing->is_available || ($listing->status && $listing->status !== 'active')) {
            abort(404, 'Listing not available');
        }
        
        return view('listings.show', compact('listing'));
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Create the listing
        $listing = new Listing();
        $listing->user_id = Auth::id();
        $listing->type = $validated['listing_type'];
        $listing->location = $validated['location'];
        $listing->price = $validated['min_price'];
        $listing->min_price = $validated['min_price'];
        $listing->max_price = $validated['max_price'];
        $listing->description = $validated['description'];
        $listing->latitude = $validated['latitude'];
        $listing->longitude = $validated['longitude'];
        $listing->status = 'pending';
        $listing->is_available = true;
        $listing->save();

        // Redirect to public listings page with success message
        return redirect()->route('listings.index')->with('success', 'Listing created successfully! Your listing is now pending approval.');
    }
}
