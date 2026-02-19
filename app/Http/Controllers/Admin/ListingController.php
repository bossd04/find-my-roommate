<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with('landlord')->latest()->paginate(15);
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
        'property_type' => 'required|string|in:apartment,house,condo,room',
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

    // Set default type if not provided
    $validated['type'] = $validated['property_type'] ?? 'apartment';
    
    // Set default security_deposit to 0 if not provided
    $validated['security_deposit'] = $validated['security_deposit'] ?? 0;
    
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

    return redirect()->route('admin.listings.index')
        ->with('success', 'Listing created successfully');
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
}
