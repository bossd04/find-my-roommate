@extends('admin.layouts.app')

@section('title', 'Create New Listing')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Create New Listing</h2>
            </div>
            
            <form action="{{ route('admin.listings.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (₱)</label>
                            <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Bedrooms -->
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" min="1" value="{{ old('bedrooms', 1) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('bedrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" min="1" value="{{ old('bathrooms', 1) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('bathrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label for="property_type" class="block text-sm font-medium text-gray-700">Property Type</label>
                            <select name="property_type" id="property_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>House</option>
                                <option value="condo" {{ old('property_type') == 'condo' ? 'selected' : '' }}>Condominium</option>
                                <option value="dormitory" {{ old('property_type') == 'dormitory' ? 'selected' : '' }}>Dormitory</option>
                                <option value="bedspace" {{ old('property_type') == 'bedspace' ? 'selected' : '' }}>Bedspace</option>
                            </select>
                            @error('property_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Main Image</label>
                            <input type="file" name="image" id="image" accept="image/*" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_available" id="is_available" value="1" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                            <label for="is_available" class="ml-2 block text-sm text-gray-700">
                                Make this listing active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Additional Fields -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Area (sqm) -->
                        <div>
                            <label for="area_sqft" class="block text-sm font-medium text-gray-700">Area (sqm)</label>
                            <input type="number" name="area_sqft" id="area_sqft" min="0" step="0.01" value="{{ old('area_sqft') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Furnished -->
                        <div class="flex items-center">
                            <input type="checkbox" name="furnished" id="furnished" value="1" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('furnished') ? 'checked' : '' }}>
                            <label for="furnished" class="ml-2 block text-sm text-gray-700">
                                Furnished
                            </label>
                        </div>

                        <!-- Utilities Included -->
                        <div class="flex items-center">
                            <input type="checkbox" name="utilities_included" id="utilities_included" value="1" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('utilities_included') ? 'checked' : '' }}>
                            <label for="utilities_included" class="ml-2 block text-sm text-gray-700">
                                Utilities Included
                            </label>
                        </div>

                        <!-- Available From -->
                        <div>
                            <label for="available_from" class="block text-sm font-medium text-gray-700">Available From</label>
                            <input type="date" name="available_from" id="available_from" value="{{ old('available_from') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Lease Duration (months) -->
                        <div>
                            <label for="lease_duration_months" class="block text-sm font-medium text-gray-700">Lease Duration (months)</label>
                            <input type="number" name="lease_duration_months" id="lease_duration_months" min="1" value="{{ old('lease_duration_months', 12) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Security Deposit -->
                        <div>
                            <label for="security_deposit" class="block text-sm font-medium text-gray-700">Security Deposit (₱)</label>
                            <input type="number" name="security_deposit" id="security_deposit" min="0" step="0.01" value="{{ old('security_deposit') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @php
                                $amenities = [
                                    'wifi' => 'WiFi',
                                    'aircon' => 'Air Conditioning',
                                    'kitchen' => 'Kitchen',
                                    'laundry' => 'Laundry',
                                    'tv' => 'TV',
                                    'refrigerator' => 'Refrigerator',
                                    'parking' => 'Parking',
                                    'security' => '24/7 Security',
                                    'gym' => 'Gym',
                                    'pool' => 'Swimming Pool',
                                    'elevator' => 'Elevator',
                                    'furnished' => 'Fully Furnished'
                                ];
                                $selectedAmenities = old('amenities', []);
                            @endphp
                            
                            @foreach($amenities as $value => $label)
                                <div class="flex items-center">
                                    <input type="checkbox" name="amenities[]" id="amenity_{{ $value }}" value="{{ $value }}" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                           {{ in_array($value, $selectedAmenities) ? 'checked' : '' }}>
                                    <label for="amenity_{{ $value }}" class="ml-2 block text-sm text-gray-700">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- House Rules -->
                    <div class="mt-6">
                        <label for="house_rules" class="block text-sm font-medium text-gray-700">House Rules</label>
                        <textarea name="house_rules" id="house_rules" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('house_rules') }}</textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.listings.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize date picker for available_from field
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today for available_from
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('available_from').min = today;
    });
</script>
@endpush

@endsection
