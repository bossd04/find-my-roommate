@extends('layouts.app-with-sidebar')

@section('content')
    @if (session('success'))
    <div id="success-notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-y-0 opacity-100">
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Hero Section with Background -->
    <div class="mb-8 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl" 
         style="background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 50%, #EC4899 100%);">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute top-20 right-20 w-32 h-32 bg-blue-200 rounded-full blur-2xl animate-pulse delay-75"></div>
                    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-purple-200 rounded-full blur-3xl animate-pulse delay-100"></div>
                    <div class="absolute bottom-10 right-10 w-24 h-24 bg-pink-200 rounded-full blur-xl animate-pulse delay-150"></div>
                </div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2">Create Listing 🏠</h1>
                    <p class="text-blue-100 text-lg">Share your room or apartment with potential roommates.</p>
                </div>
            </div>
                    <form id="create-listing-form" action="{{ route('listings.store') }}" method="POST">
                        @csrf
                        
                        <!-- Listing Type -->
                        <div class="mb-6">
                            <label for="listing_type" class="block text-sm font-medium text-gray-700">
                                I am looking to
                            </label>
                            <select id="listing_type" name="listing_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="room">Rent out a room</option>
                                <option value="roommate">Find a roommate</option>
                                <option value="apartment">Find an apartment to share</option>
                            </select>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700">
                                Location
                            </label>
                            <input type="text" name="location" id="location" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Enter city or neighborhood">
                        </div>

                        <!-- Map Container with Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Location on Map
                            </label>
                            
                            <!-- Location Search Bar -->
                            <div class="relative mb-3">
                                <div class="flex">
                                    <input type="text" 
                                           id="location-search" 
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Search for a location (e.g., Dagupan City, Lyceum Northwestern)...">
                                    <button type="button" 
                                            id="search-btn"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Search Results Dropdown -->
                                <div id="search-results" class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 hidden max-h-60 overflow-y-auto">
                                    <!-- Results will be populated here -->
                                </div>
                            </div>
                            
                            <!-- My Location Button -->
                            <div class="mb-3 flex justify-between items-center">
                                <button type="button" 
                                        id="my-location-btn"
                                        class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-1.414 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Show My Location
                                </button>
                                <div id="distance-display" class="text-sm text-gray-600 hidden">
                                    <span class="font-semibold text-indigo-600">Distance: <span id="distance-value">-</span></span>
                                </div>
                            </div>
                            
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px; border: 2px solid #e5e7eb; background-color: #e5e7eb; z-index: 1;"></div>
                        </div>

                        <!-- Coordinate Inputs -->
                        <div class="p-4 border-t border-green-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="lat" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                                    <input type="text" 
                                           name="latitude" 
                                           id="lat" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                           placeholder="Click map or type latitude">
                                </div>
                                <div>
                                    <label for="lng" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                                    <input type="text" 
                                           name="longitude" 
                                           id="lng" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white"
                                           placeholder="Click map or type longitude">
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Price Range (per month)
                            </label>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_price" class="sr-only">Min</label>
                                    <input type="number" name="min_price" id="min_price" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Min">
                                </div>
                                <div>
                                    <label for="max_price" class="sr-only">Max</label>
                                    <input type="number" name="max_price" id="max_price" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Tell potential roommates about yourself, your living situation, and what you're looking for..."></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="button" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="submit" id="submit-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <span id="button-text">Create Listing</span>
                                <svg id="loading-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                    
                    @push('scripts')
                    <style>
                        .search-result-item {
                            padding: 12px 16px;
                            cursor: pointer;
                            border-bottom: 1px solid #e5e7eb;
                            transition: background-color 0.2s;
                        }
                        .search-result-item:hover {
                            background-color: #f3f4f6;
                        }
                        .search-result-item:last-child {
                            border-bottom: none;
                        }
                        .search-result-name {
                            font-weight: 600;
                            color: #374151;
                            font-size: 14px;
                        }
                        .search-result-address {
                            font-size: 12px;
                            color: #6b7280;
                            margin-top: 2px;
                        }
                    </style>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('create-listing-form');
                            const submitButton = document.getElementById('submit-button');
                            const buttonText = document.getElementById('button-text');
                            const loadingSpinner = document.getElementById('loading-spinner');
                            const mapContainer = document.getElementById('map');
                            const searchInput = document.getElementById('location-search');
                            const searchBtn = document.getElementById('search-btn');
                            const searchResults = document.getElementById('search-results');
                            
                            // Location search variables
                            let searchTimeout;
                            let map, marker, userMarker, userLocation = null;
                            let latInput = document.getElementById('lat');
                            let lngInput = document.getElementById('lng');
                            let locationInput = document.getElementById('location');
                            const myLocationBtn = document.getElementById('my-location-btn');
                            const distanceDisplay = document.getElementById('distance-display');
                            const distanceValue = document.getElementById('distance-value');
                            
                            if (form) {
                                form.addEventListener('submit', function() {
                                    submitButton.disabled = true;
                                    buttonText.textContent = 'Creating...';
                                    loadingSpinner.classList.remove('hidden');
                                });
                            }
                            
                            // Hide success notification after 5 seconds
                            const successNotification = document.getElementById('success-notification');
                            if (successNotification) {
                                setTimeout(() => {
                                    successNotification.classList.add('translate-y-4', 'opacity-0');
                                    setTimeout(() => {
                                        successNotification.remove();
                                    }, 300);
                                }, 5000);
                            }

                            // Initialize Leaflet Map
                            if (mapContainer && typeof L !== 'undefined') {
                                map = L.map('map', {
                                    minZoom: 1,
                                    maxZoom: 25,
                                    zoomControl: true
                                }).setView([16.0430, 120.3333], 13);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);

                                // Map click handler
                                map.on('click', function(e) {
                                    if (marker) {
                                        map.removeLayer(marker);
                                    }

                                    marker = L.marker(e.latlng).addTo(map);

                                    latInput.value = e.latlng.lat.toFixed(7);
                                    lngInput.value = e.latlng.lng.toFixed(7);
                                    
                                    // Reverse geocode to get location name
                                    reverseGeocode(e.latlng.lat, e.latlng.lng);
                                });

                                // Input change handlers
                                latInput.addEventListener('change', updateMarkerFromInputs);
                                lngInput.addEventListener('change', updateMarkerFromInputs);

                                function updateMarkerFromInputs() {
                                    var lat = parseFloat(latInput.value);
                                    var lng = parseFloat(lngInput.value);

                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        if (marker) {
                                            map.removeLayer(marker);
                                        }
                                        marker = L.marker([lat, lng]).addTo(map);
                                        map.setView([lat, lng], 15);
                                        
                                        // Update distance if user location is available
                                        updateDistanceDisplay();
                                    }
                                }
                                
                                // User location functionality
                                if (myLocationBtn) {
                                    myLocationBtn.addEventListener('click', function() {
                                        if (navigator.geolocation) {
                                            myLocationBtn.innerHTML = `
                                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Getting location...
                                            `;
                                            
                                            navigator.geolocation.getCurrentPosition(
                                                function(position) {
                                                    const lat = position.coords.latitude;
                                                    const lng = position.coords.longitude;
                                                    userLocation = { lat, lng };
                                                    
                                                    // Add or update user marker
                                                    if (userMarker) {
                                                        map.removeLayer(userMarker);
                                                    }
                                                    
                                                    // Create custom user location icon
                                                    const userIcon = L.divIcon({
                                                        className: 'custom-user-marker',
                                                        html: '<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>',
                                                        iconSize: [20, 20],
                                                        iconAnchor: [10, 10]
                                                    });
                                                    
                                                    userMarker = L.marker([lat, lng], { icon: userIcon })
                                                        .addTo(map)
                                                        .bindPopup('<b>Your Location</b>')
                                                        .openPopup();
                                                    
                                                    // Center map on user location
                                                    map.setView([lat, lng], 15);
                                                    
                                                    // Reset button
                                                    myLocationBtn.innerHTML = `
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-1.414 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Show My Location
                                                    `;
                                                    
                                                    // Update distance display
                                                    updateDistanceDisplay();
                                                },
                                                function(error) {
                                                    console.error('Geolocation error:', error);
                                                    alert('Could not get your location. Please ensure location services are enabled.');
                                                    myLocationBtn.innerHTML = `
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-1.414 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Show My Location
                                                    `;
                                                },
                                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                                            );
                                        } else {
                                            alert('Geolocation is not supported by your browser.');
                                        }
                                    });
                                }
                                
                                // Calculate distance between two points using Haversine formula
                                function calculateDistance(lat1, lng1, lat2, lng2) {
                                    const R = 6371; // Earth's radius in kilometers
                                    const dLat = (lat2 - lat1) * Math.PI / 180;
                                    const dLng = (lng2 - lng1) * Math.PI / 180;
                                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                              Math.sin(dLng/2) * Math.sin(dLng/2);
                                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                                    return R * c;
                                }
                                
                                // Update distance display
                                function updateDistanceDisplay() {
                                    if (userLocation && latInput.value && lngInput.value) {
                                        const listingLat = parseFloat(latInput.value);
                                        const listingLng = parseFloat(lngInput.value);
                                        
                                        if (!isNaN(listingLat) && !isNaN(listingLng)) {
                                            const distance = calculateDistance(
                                                userLocation.lat, userLocation.lng,
                                                listingLat, listingLng
                                            );
                                            
                                            // Format distance
                                            let distanceText;
                                            if (distance < 1) {
                                                distanceText = (distance * 1000).toFixed(0) + ' m';
                                            } else {
                                                distanceText = distance.toFixed(2) + ' km';
                                            }
                                            
                                            distanceValue.textContent = distanceText;
                                            distanceDisplay.classList.remove('hidden');
                                        }
                                    } else {
                                        distanceDisplay.classList.add('hidden');
                                    }
                                }
                                
                                // Update distance when marker changes
                                map.on('click', function() {
                                    setTimeout(updateDistanceDisplay, 100);
                                });
                                
                                // Pangasinan cities and municipalities list
                                const pangasinanLocations = [
                                    'Alaminos City', 'Dagupan City', 'San Carlos City', 'Urdaneta City',
                                    'Agno', 'Aguilar', 'Alcala', 'Anda', 'Asingan', 'Balungao', 'Bani', 'Basista',
                                    'Bautista', 'Bayambang', 'Binalonan', 'Binmaley', 'Bolinao', 'Bugallon', 'Burgos',
                                    'Calasiao', 'Dasol', 'Infanta', 'Labrador', 'Laoac', 'Lingayen', 'Mabini',
                                    'Malasiqui', 'Mangaldan', 'Mangatarem', 'Mapandan', 'Natividad', 'Pozorrubio',
                                    'Rosales', 'Sison', 'San Fabian', 'San Jacinto', 'San Manuel', 'San Nicolas',
                                    'San Quintin', 'Santa Barbara', 'Santa Maria', 'Santo Tomas', 'Sual', 'Tayug',
                                    'Umingan', 'Urbiztondo', 'Villasis'
                                ];
                                
                                // Search functionality
                                function performSearch(query) {
                                    if (!query || query.length < 3) return;
                                    
                                    // Check if user already specified a city/municipality
                                    let searchQuery = query;
                                    const hasCity = pangasinanLocations.some(city => 
                                        query.toLowerCase().includes(city.toLowerCase())
                                    );
                                    
                                    // Add Pangasinan if no location context provided
                                    if (!hasCity && !query.toLowerCase().includes('pangasinan')) {
                                        searchQuery = `${query}, Pangasinan, Philippines`;
                                    }
                                    
                                    // Use Nominatim geocoding API with Pangasinan province focus
                                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=15&countrycodes=ph&viewbox=119.5%2C16.6%2C120.8%2C15.4&bounded=0&addressdetails=1`;
                                    
                                    fetch(url)
                                        .then(response => response.json())
                                        .then(data => {
                                            // Filter results to prioritize Pangasinan locations
                                            const pangasinanResults = data.filter(result => {
                                                const name = (result.display_name || '').toLowerCase();
                                                return name.includes('pangasinan') || 
                                                       pangasinanLocations.some(city => name.includes(city.toLowerCase()));
                                            });
                                            
                                            // If we have Pangasinan results, show them; otherwise show all
                                            displaySearchResults(pangasinanResults.length > 0 ? pangasinanResults : data);
                                        })
                                        .catch(error => {
                                            console.error('Search error:', error);
                                        });
                                }
                                
                                function displaySearchResults(results) {
                                    searchResults.innerHTML = '';
                                    
                                    if (results.length === 0) {
                                        searchResults.innerHTML = '<div class="p-4 text-gray-500 text-center">No results found</div>';
                                        searchResults.classList.remove('hidden');
                                        return;
                                    }
                                    
                                    results.forEach((result, index) => {
                                        const div = document.createElement('div');
                                        div.className = 'search-result-item';
                                        div.innerHTML = `
                                            <div class="search-result-name">${result.display_name.split(',')[0]}</div>
                                            <div class="search-result-address">${result.display_name}</div>
                                        `;
                                        div.addEventListener('click', () => {
                                            selectLocation(result);
                                        });
                                        searchResults.appendChild(div);
                                    });
                                    
                                    searchResults.classList.remove('hidden');
                                }
                                
                                function selectLocation(result) {
                                    const lat = parseFloat(result.lat);
                                    const lon = parseFloat(result.lon);
                                    
                                    // Update map
                                    map.setView([lat, lon], 16);
                                    
                                    if (marker) {
                                        map.removeLayer(marker);
                                    }
                                    marker = L.marker([lat, lon]).addTo(map);
                                    
                                    // Update inputs
                                    latInput.value = lat.toFixed(7);
                                    lngInput.value = lon.toFixed(7);
                                    locationInput.value = result.display_name;
                                    
                                    // Update distance if user location is available
                                    updateDistanceDisplay();
                                    
                                    // Hide search results
                                    searchResults.classList.add('hidden');
                                    searchInput.value = result.display_name;
                                }
                                
                                function reverseGeocode(lat, lng) {
                                    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
                                    
                                    fetch(url)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && data.display_name) {
                                                locationInput.value = data.display_name;
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Reverse geocode error:', error);
                                        });
                                }
                                
                                // Search input event listeners
                                searchInput.addEventListener('input', function() {
                                    clearTimeout(searchTimeout);
                                    const query = this.value.trim();
                                    
                                    if (query.length >= 3) {
                                        searchTimeout = setTimeout(() => {
                                            performSearch(query);
                                        }, 500);
                                    } else {
                                        searchResults.classList.add('hidden');
                                    }
                                });
                                
                                searchBtn.addEventListener('click', function() {
                                    const query = searchInput.value.trim();
                                    if (query.length >= 3) {
                                        performSearch(query);
                                    }
                                });
                                
                                searchInput.addEventListener('keypress', function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        const query = this.value.trim();
                                        if (query.length >= 3) {
                                            performSearch(query);
                                        }
                                    }
                                });
                                
                                // Hide search results when clicking outside
                                document.addEventListener('click', function(e) {
                                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target) && !searchBtn.contains(e.target)) {
                                        searchResults.classList.add('hidden');
                                    }
                                });
                            }
                        });
                    </script>
                    @endpush
                </div>
            </div>
@endsection
