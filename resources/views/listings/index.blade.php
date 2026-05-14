@extends('layouts.app')

@section('title', 'Room Listings')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Success Message -->
        @if (session('success'))
        <div id="success-notification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center transform transition-all duration-300 z-50">
            <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <p class="font-semibold">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black text-gray-900 dark:text-gray-50 mb-4">
                Available Room Listings
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Find your perfect roommate from our curated selection of available rooms and apartments
            </p>
        </div>

        <!-- Filters Section -->
        <form action="{{ route('listings.index') }}" method="GET" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           placeholder="Search listings..." 
                           value="{{ request('search') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                    <select name="location" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">All Locations</option>
                        <optgroup label="Cities">
                            <option value="Alaminos City" {{ request('location') == 'Alaminos City' ? 'selected' : '' }}>Alaminos City</option>
                            <option value="Dagupan City" {{ request('location') == 'Dagupan City' ? 'selected' : '' }}>Dagupan City</option>
                            <option value="San Carlos City" {{ request('location') == 'San Carlos City' ? 'selected' : '' }}>San Carlos City</option>
                            <option value="Urdaneta City" {{ request('location') == 'Urdaneta City' ? 'selected' : '' }}>Urdaneta City</option>
                        </optgroup>
                        <optgroup label="Municipalities">
                            <option value="Agno" {{ request('location') == 'Agno' ? 'selected' : '' }}>Agno</option>
                            <option value="Aguilar" {{ request('location') == 'Aguilar' ? 'selected' : '' }}>Aguilar</option>
                            <option value="Alcala" {{ request('location') == 'Alcala' ? 'selected' : '' }}>Alcala</option>
                            <option value="Anda" {{ request('location') == 'Anda' ? 'selected' : '' }}>Anda</option>
                            <option value="Asingan" {{ request('location') == 'Asingan' ? 'selected' : '' }}>Asingan</option>
                            <option value="Balungao" {{ request('location') == 'Balungao' ? 'selected' : '' }}>Balungao</option>
                            <option value="Bani" {{ request('location') == 'Bani' ? 'selected' : '' }}>Bani</option>
                            <option value="Basista" {{ request('location') == 'Basista' ? 'selected' : '' }}>Basista</option>
                            <option value="Bautista" {{ request('location') == 'Bautista' ? 'selected' : '' }}>Bautista</option>
                            <option value="Bayambang" {{ request('location') == 'Bayambang' ? 'selected' : '' }}>Bayambang</option>
                            <option value="Binalonan" {{ request('location') == 'Binalonan' ? 'selected' : '' }}>Binalonan</option>
                            <option value="Binmaley" {{ request('location') == 'Binmaley' ? 'selected' : '' }}>Binmaley</option>
                            <option value="Bolinao" {{ request('location') == 'Bolinao' ? 'selected' : '' }}>Bolinao</option>
                            <option value="Buenavista" {{ request('location') == 'Buenavista' ? 'selected' : '' }}>Buenavista</option>
                            <option value="Bugallon" {{ request('location') == 'Bugallon' ? 'selected' : '' }}>Bugallon</option>
                            <option value="Burgos" {{ request('location') == 'Burgos' ? 'selected' : '' }}>Burgos</option>
                            <option value="Calasiao" {{ request('location') == 'Calasiao' ? 'selected' : '' }}>Calasiao</option>
                            <option value="Dasol" {{ request('location') == 'Dasol' ? 'selected' : '' }}>Dasol</option>
                            <option value="Herrera" {{ request('location') == 'Herrera' ? 'selected' : '' }}>Herrera</option>
                            <option value="Infanta" {{ request('location') == 'Infanta' ? 'selected' : '' }}>Infanta</option>
                            <option value="Labrador" {{ request('location') == 'Labrador' ? 'selected' : '' }}>Labrador</option>
                            <option value="Laoac" {{ request('location') == 'Laoac' ? 'selected' : '' }}>Laoac</option>
                            <option value="Lingayen" {{ request('location') == 'Lingayen' ? 'selected' : '' }}>Lingayen</option>
                            <option value="Mabini" {{ request('location') == 'Mabini' ? 'selected' : '' }}>Mabini</option>
                            <option value="Malasiqui" {{ request('location') == 'Malasiqui' ? 'selected' : '' }}>Malasiqui</option>
                            <option value="Mangaldan" {{ request('location') == 'Mangaldan' ? 'selected' : '' }}>Mangaldan</option>
                            <option value="Mapandan" {{ request('location') == 'Mapandan' ? 'selected' : '' }}>Mapandan</option>
                            <option value="Natividad" {{ request('location') == 'Natividad' ? 'selected' : '' }}>Natividad</option>
                            <option value="Pozorrubio" {{ request('location') == 'Pozorrubio' ? 'selected' : '' }}>Pozorrubio</option>
                            <option value="Quezon" {{ request('location') == 'Quezon' ? 'selected' : '' }}>Quezon</option>
                            <option value="Rosales" {{ request('location') == 'Rosales' ? 'selected' : '' }}>Rosales</option>
                            <option value="Rosario" {{ request('location') == 'Rosario' ? 'selected' : '' }}>Rosario</option>
                            <option value="San Fabian" {{ request('location') == 'San Fabian' ? 'selected' : '' }}>San Fabian</option>
                            <option value="San Jacinto" {{ request('location') == 'San Jacinto' ? 'selected' : '' }}>San Jacinto</option>
                            <option value="San Manuel" {{ request('location') == 'San Manuel' ? 'selected' : '' }}>San Manuel</option>
                            <option value="San Nicolas" {{ request('location') == 'San Nicolas' ? 'selected' : '' }}>San Nicolas</option>
                            <option value="San Quintin" {{ request('location') == 'San Quintin' ? 'selected' : '' }}>San Quintin</option>
                            <option value="Santa Barbara" {{ request('location') == 'Santa Barbara' ? 'selected' : '' }}>Santa Barbara</option>
                            <option value="Santa Maria" {{ request('location') == 'Santa Maria' ? 'selected' : '' }}>Santa Maria</option>
                            <option value="Santo Tomas" {{ request('location') == 'Santo Tomas' ? 'selected' : '' }}>Santo Tomas</option>
                            <option value="Sison" {{ request('location') == 'Sison' ? 'selected' : '' }}>Sison</option>
                            <option value="Tayug" {{ request('location') == 'Tayug' ? 'selected' : '' }}>Tayug</option>
                            <option value="Umingan" {{ request('location') == 'Umingan' ? 'selected' : '' }}>Umingan</option>
                            <option value="Urbiztondo" {{ request('location') == 'Urbiztondo' ? 'selected' : '' }}>Urbiztondo</option>
                            <option value="Villasis" {{ request('location') == 'Villasis' ? 'selected' : '' }}>Villasis</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price Range</label>
                    <select name="price_range" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">All Prices</option>
                        <option value="0-3000" {{ request('price_range') == '0-3000' ? 'selected' : '' }}>₱0 - ₱3,000</option>
                        <option value="3000-5000" {{ request('price_range') == '3000-5000' ? 'selected' : '' }}>₱3,000 - ₱5,000</option>
                        <option value="5000-8000" {{ request('price_range') == '5000-8000' ? 'selected' : '' }}>₱5,000 - ₱8,000</option>
                        <option value="8000+" {{ request('price_range') == '8000+' ? 'selected' : '' }}>₱8,000+</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'location', 'price_range']))
                    <a href="{{ route('listings.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center" title="Clear filters">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Map Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50 flex items-center">
                    <i class="fas fa-map-marked-alt mr-2 text-indigo-600"></i>
                    Listing Locations
                </h2>
                <button onclick="centerOnUserLocation()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center">
                    <i class="fas fa-crosshairs mr-2"></i>
                    My Location
                </button>
            </div>
            <div id="map" style="height: 400px;"></div>
            <div class="mt-3 flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-indigo-600 mr-2"></span>
                    Available Listings
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                    Your Location
                </div>
            </div>
        </div>

        <!-- Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($listings as $listing)
                <div id="listing-{{ $listing->id }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                    <!-- Image -->
                    <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                        @if($listing->image)
                            <img src="{{ route('listing.image.serve', ['filename' => basename($listing->image)]) }}" 
                                 alt="{{ $listing->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-home text-4xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        @if($listing->is_available)
                            <span class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                Available
                            </span>
                        @else
                            <span class="absolute top-4 right-4 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                Occupied
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-2">
                            {{ $listing->title }}
                        </h3>
                        
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $listing->location }}
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                ₱{{ number_format($listing->price, 0) }}
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">/month</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-bed mr-1"></i>
                                {{ $listing->bedrooms }} bed
                                <i class="fas fa-bath ml-2 mr-1"></i>
                                {{ $listing->bathrooms }} bath
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ Str::limit($listing->description, 100) }}
                        </div>

                        <!-- Owner Info -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mr-2">
                                    @if($listing->user)
                                        {{ strtoupper(substr($listing->user->first_name, 0, 1)) }}
                                    @elseif($listing->landlord)
                                        <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 text-xs"></i>
                                    @else
                                        <i class="fas fa-user text-indigo-600 dark:text-indigo-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="text-sm">
                                    @if($listing->user)
                                        {{ $listing->user->full_name }}
                                    @elseif($listing->landlord)
                                        Admin Listed
                                    @else
                                        Unknown
                                    @endif
                                </div>
                            </div>
                            
                            <a href="{{ route('listings.show', $listing) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium text-sm">
                                View Details
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-home text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50 mb-2">No listings available</h3>
                    <p class="text-gray-600 dark:text-gray-400">Check back later for new room listings</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $listings->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Collect all listing coordinates
    var listings = [
        @foreach($listings as $listing)
            @if($listing->latitude && $listing->longitude)
                {
                    id: {{ $listing->id }},
                    lat: {{ $listing->latitude }},
                    lng: {{ $listing->longitude }},
                    title: "{{ addslashes($listing->title) }}",
                    location: "{{ addslashes($listing->location) }}",
                    price: "₱{{ number_format($listing->price, 0) }}",
                    available: {{ $listing->is_available ? 'true' : 'false' }}
                },
            @endif
        @endforeach
    ];

    // Initialize map
    var defaultLat = 16.0430; // Dagupan City center
    var defaultLng = 120.3333;
    var mapZoom = 12;

    // Create map instance with no zoom limits
    var map = L.map('map', {
        minZoom: 1,
        maxZoom: 19
    }).setView([defaultLat, defaultLng], mapZoom);

    // Add OpenStreetMap tiles with high zoom support
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
        maxNativeZoom: 19
    }).addTo(map);

    // Store markers for later reference
    var markers = {};
    var userMarker = null;

    // Custom icons
    var listingIcon = L.divIcon({
        className: 'custom-listing-marker',
        html: '<div style="background-color: #4F46E5; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="fas fa-home" style="color: white; font-size: 10px;"></i></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    var userIcon = L.divIcon({
        className: 'custom-user-marker',
        html: '<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    // Add markers for all listings with coordinates
    listings.forEach(function(listing) {
        var marker = L.marker([listing.lat, listing.lng], {icon: listingIcon}).addTo(map);
        
        marker.bindPopup(
            '<div style="min-width: 200px;">' +
            '<b style="font-size: 14px; color: #1F2937;">' + listing.title + '</b><br>' +
            '<span style="color: #6B7280; font-size: 12px;">' + listing.location + '</span><br>' +
            '<span style="color: #4F46E5; font-weight: bold; font-size: 14px;">' + listing.price + '/month</span><br>' +
            '<button onclick="scrollToListing(' + listing.id + ')" style="margin-top: 8px; padding: 4px 12px; background: #4F46E5; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">View Details</button>' +
            '</div>'
        );
        
        marker.on('click', function() {
            highlightListing(listing.id);
        });
        
        markers[listing.id] = marker;
    });

    // Fit bounds to show all markers if listings exist
    if (listings.length > 0) {
        var group = new L.featureGroup(Object.values(markers));
        map.fitBounds(group.getBounds().pad(0.1));
    }

    // Get user's current location
    function centerOnUserLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                
                // Remove existing user marker
                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                
                // Add user location marker
                userMarker = L.marker([userLat, userLng], {icon: userIcon}).addTo(map);
                userMarker.bindPopup('<b>Your Location</b>').openPopup();
                
                // Center map on user location
                map.setView([userLat, userLng], 15);
                
            }, function(error) {
                alert('Unable to get your location. Please check your browser permissions.');
                console.error('Geolocation error:', error);
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }

    // Geocode location search and center map
    function geocodeLocation(locationName) {
        if (!locationName) return;
        
        // Use Nominatim API for geocoding
        var url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(locationName + ', Pangasinan, Philippines');
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    
                    // Center map on searched location
                    map.setView([lat, lon], 14);
                    
                    // Show a temporary circle at the searched location
                    L.circle([lat, lon], {
                        color: '#4F46E5',
                        fillColor: '#4F46E5',
                        fillOpacity: 0.1,
                        radius: 1000
                    }).addTo(map).bindPopup('Search: ' + locationName).openPopup();
                    
                } else {
                    console.log('Location not found:', locationName);
                }
            })
            .catch(error => {
                console.error('Geocoding error:', error);
            });
    }

    // Scroll to listing card when marker is clicked
    function scrollToListing(listingId) {
        var element = document.getElementById('listing-' + listingId);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            highlightListing(listingId);
        }
    }

    // Highlight listing card
    function highlightListing(listingId) {
        // Remove highlight from all listings
        document.querySelectorAll('[id^="listing-"]').forEach(function(el) {
            el.classList.remove('ring-4', 'ring-indigo-500', 'ring-offset-2');
        });
        
        // Add highlight to selected listing
        var element = document.getElementById('listing-' + listingId);
        if (element) {
            element.classList.add('ring-4', 'ring-indigo-500', 'ring-offset-2');
            setTimeout(function() {
                element.classList.remove('ring-4', 'ring-indigo-500', 'ring-offset-2');
            }, 3000);
        }
    }

    // Handle search form submission - geocode location
    document.querySelector('form').addEventListener('submit', function(e) {
        var locationSelect = document.querySelector('select[name="location"]');
        if (locationSelect && locationSelect.value) {
            e.preventDefault();
            geocodeLocation(locationSelect.value);
            // Still submit the form after a short delay to filter listings
            setTimeout(function() {
                e.target.submit();
            }, 1000);
        }
    });

    // Auto-geocode if location is already selected (page load with filters)
    var selectedLocation = document.querySelector('select[name="location"]')?.value;
    if (selectedLocation) {
        geocodeLocation(selectedLocation);
    }

    // Try to get user location on page load (optional)
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;
            
            // Add user location marker
            if (!userMarker) {
                userMarker = L.marker([userLat, userLng], {icon: userIcon}).addTo(map);
                userMarker.bindPopup('<b>Your Location</b>');
            }
        }, function(error) {
            // Silently fail - user can click button to get location
            console.log('Auto-geolocation not available');
        });
    }

    // Auto-hide success notification after 5 seconds
    const successNotification = document.getElementById('success-notification');
    if (successNotification) {
        setTimeout(() => {
            successNotification.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                successNotification.remove();
            }, 300);
        }, 5000);
    }
</script>
@endpush
@endsection
