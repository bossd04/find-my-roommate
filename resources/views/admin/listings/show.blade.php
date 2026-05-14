@extends('admin.layouts.app')

@section('title', 'Listing Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Listing Details</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    View and manage listing information
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-4 mt-6 md:mt-0">
                <a href="{{ route('admin.listings.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-500/20 shadow-lg transition-all active:scale-95">
                    <i class="fas fa-arrow-left mr-3"></i> Back to Listings
                </a>
                <a href="{{ route('admin.listings.edit', $listing) }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                    <i class="fas fa-edit mr-3"></i> Edit Listing
                </a>
            </div>
        </div>
    </div>

    <!-- Listing Details Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
        <div class="p-8">
            <!-- Status Badge -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-gray-50">{{ $listing->title }}</h2>
                    @if($listing->is_active)
                        <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                            Active
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                            Inactive
                        </span>
                    @endif
                </div>
                <div class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                    ID: #{{ $listing->id }}
                </div>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-50 border-b border-gray-200 dark:border-gray-700 pb-2">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Property Type</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ ucfirst($listing->property_type) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Location</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->location }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Price</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">₱{{ number_format($listing->price, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Status</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->is_available ? 'Available' : 'Not Available' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-50 border-b border-gray-200 dark:border-gray-700 pb-2">Property Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Bedrooms</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->bedrooms ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Bathrooms</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->bathrooms ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Area (sq ft)</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->area_sqft ? number_format($listing->area_sqft) : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Furnished</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->furnished ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-lg font-black text-gray-900 dark:text-gray-50 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Description</h3>
                <div class="prose prose-sm max-w-none">
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $listing->description ?: 'No description provided.' }}
                    </p>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-50 border-b border-gray-200 dark:border-gray-700 pb-2">Lease Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Lease Duration</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->lease_duration_months }} months</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Security Deposit</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">₱{{ number_format($listing->security_deposit, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Available From</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">
                                {{ $listing->available_from ? $listing->available_from->format('M j, Y') : 'Immediately' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-50 border-b border-gray-200 dark:border-gray-700 pb-2">Price Range</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Minimum Price</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">₱{{ number_format($listing->min_price, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Maximum Price</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">₱{{ number_format($listing->max_price, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Utilities Included</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $listing->utilities_included ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between text-xs text-gray-400 dark:text-gray-500">
                    <div>
                        <p>Created: {{ $listing->created_at->format('M j, Y, g:i a') }}</p>
                        <p>Last Updated: {{ $listing->updated_at->format('M j, Y, g:i a') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <p class="font-black uppercase tracking-widest">Listing ID: #{{ $listing->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.listings.edit', $listing) }}" 
           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
            <i class="fas fa-edit mr-3"></i> Edit Listing
        </a>
        
        <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" onsubmit="return confirm('Are you sure you want to delete this listing?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/20 shadow-lg shadow-red-500/30 transition-all active:scale-95">
                <i class="fas fa-trash mr-3"></i> Delete Listing
            </button>
        </form>
    </div>
</div>
@endsection
