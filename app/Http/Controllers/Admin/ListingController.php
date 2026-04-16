<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['landlord', 'user'])->latest();

        // Apply status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'active') {
                $query->where('is_active', true)->where(function($q) {
                    $q->where('status', '!=', 'pending')->orWhereNull('status');
                });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false)->where(function($q) {
                    $q->where('status', '!=', 'pending')->orWhereNull('status');
                });
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $listings = $query->paginate(15)->withQueryString();
        return view('admin.listings.index', compact('listings'));
    }

    public function create()
    {
        return view('admin.listings.create');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'location' => 'required|string|max:255',
        'bedrooms' => 'required|integer|min:1',
        'bathrooms' => 'required|integer|min:1',
        'property_type' => 'required|string|in:apartment,house,condo,room,dormitory,bedspace',
        'is_available' => 'boolean',
        'area_sqft' => 'required|numeric|min:0',
        'available_from' => 'required|date',
        'lease_duration_months' => 'required|integer|min:1',
        'security_deposit' => 'nullable|numeric|min:0',
        'house_rules' => 'nullable|string',
        'amenities' => 'nullable|array',
        'amenities.*' => 'string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Set default values
    $validated['type'] = $validated['property_type'] ?? 'apartment';
    $validated['security_deposit'] = $validated['security_deposit'] ?? 0;
    $validated['is_available'] = true;
    $validated['is_active'] = true; // Auto-approved since admin created it
    $validated['status'] = 'active'; // Auto-approved
    
    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('listings', 'public');
        $validated['image'] = $imagePath;
    }

    // Convert amenities array to JSON
    if (isset($validated['amenities'])) {
        $validated['amenities'] = json_encode($validated['amenities']);
    }

    // Set the landlord_id to the currently authenticated admin's ID
    $validated['landlord_id'] = auth('admin')->id();

    $listing = Listing::create($validated);

    // Immediately dispatch notifications since the admin created this directly.
    $this->createNewListingNotifications($listing);

    return redirect()->route('admin.listings.index')
        ->with('success', 'Listing created successfully! It is now live and users have been notified.');
}

    /**
     * Create notifications for all users about new listing
     */
    private function createNewListingNotifications(Listing $listing)
    {
        // Get ALL users including newly registered ones
        $users = User::where(function($query) {
            $query->where('is_admin', 0)
                  ->orWhereNull('is_admin');
        })->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_listing',
                'title' => 'New Listing Available! 🏠',
                'message' => "A new listing '{$listing->title}' by " . ($listing->user->full_name ?? $listing->user->name ?? 'the Admin') . " is now available in {$listing->location} for ₱" . number_format($listing->price ?? 0),
                'data' => [
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->title,
                    'listing_location' => $listing->location,
                    'listing_price' => $listing->price,
                    'property_type' => $listing->property_type,
                ],
            ]);
        }
    }

    public function show(Listing $listing)
    {
        return view('admin.listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        return view('admin.listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'is_available' => 'boolean',
        ]);

        $listing->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing updated successfully');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing deleted successfully');
    }

    public function toggleStatus(Listing $listing)
    {
        $listing->update([
            'is_available' => !$listing->is_available
        ]);

        return back()->with('success', 'Listing status updated');
    }

    public function approve(Listing $listing)
    {
        // Only approve pending listings
        if ($listing->status !== 'pending') {
            return back()->with('error', 'Only pending listings can be approved');
        }

        $listing->update([
            'status' => 'active',
            'is_active' => true,
            'is_available' => true
        ]);

        // Create notifications for all users about the newly approved listing
        $this->createNewListingNotifications($listing);

        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing approved successfully! Users will be notified.');
    }
}
