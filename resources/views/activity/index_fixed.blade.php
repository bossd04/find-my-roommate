<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('Activity Feed') }}
            </h2>
            <div class="relative">
                <input type="text" placeholder="Search activities..." class="pl-10 pr-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="bg-black bg-opacity-50 min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-xl p-6 mb-8 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Activity Feed</h1>
                            <p class="text-white/80">Your latest interactions and updates</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">{{ $activities->count() }}</div>
                                <div class="text-sm text-white/70">Activities</div>
                            </div>
                            <button onclick="showFilterModal()" class="px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-sm text-white hover:bg-white/30 transition-all duration-200 flex items-center">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                                @if(request('type') || request('search'))
                                    <span class="ml-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-white/30 text-xs font-medium text-white">
                                        {{ (request('type') ? 1 : 0) + (request('search') ? 1 : 0) }}
                                    </span>
                                @endif
                            </button>
                        </div>
                    </div>

                <!-- Filter Modal -->
                <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-md transform transition-all border border-white/20">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Filter Activities</h3>
                                    <button onclick="closeFilterModal()" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <form id="filter-form" method="GET" action="{{ route('activity.index') }}">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                                            <select name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white/50">
                                                <option value="">All Activities</option>
                                                <option value="new_match" {{ request('type') == 'new_match' ? 'selected' : '' }}>New Matches</option>
                                                <option value="message" {{ request('type') == 'message' ? 'selected' : '' }}>Messages</option>
                                                <option value="profile_view" {{ request('type') == 'profile_view' ? 'selected' : '' }}>Profile Views</option>
                                                <option value="listing_approved" {{ request('type') == 'listing_approved' ? 'selected' : '' }}>Listing Updates</option>
                                                <option value="new_feature" {{ request('type') == 'new_feature' ? 'selected' : '' }}>New Features</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-white/50" 
                                                   placeholder="Search activities...">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" onclick="resetFilters()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Reset
                                        </button>
                                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Apply Filters
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <!-- Main Content -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl overflow-hidden border border-white/20">
                    <!-- USER CARDS GRID -->
                    <div class="p-6 bg-white/5">
                        @if(isset($users) && $users->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch" id="users-container">
                                @foreach($users as $user)
                                <div class="group rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:scale-105 hover:shadow-2xl user-card border border-white/20 bg-transparent" data-user-id="{{ $user->id }}">
                                    <!-- Enhanced Profile Header -->
                                    <div class="relative h-64">
                                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400"></div>
                                        @if($user->avatar)
                                            <img class="h-64 w-full object-cover opacity-90" src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" alt="Profile">
                                        @else
                                            <div class="h-64 w-full bg-gradient-to-br from-indigo-600/80 to-purple-600/80 flex items-center justify-center">
                                                <div class="text-center">
                                                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-3">
                                                        <span class="text-4xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <p class="text-white font-medium">No Photo</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                        
                                        <!-- Profile Info Overlay -->
                                        <div class="absolute bottom-0 left-0 right-0 p-6">
                                            <div class="flex items-end space-x-4">
                                                <div class="w-16 h-16 rounded-full border-2 border-white/50 bg-white/10 backdrop-blur-sm flex items-center justify-center overflow-hidden shadow-lg">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-white font-bold text-xl mb-1 drop-shadow-lg">{{ $user->name }}</h3>
                                                    <div class="flex items-center space-x-3 text-white/95 text-sm font-medium drop-shadow">
                                                        @if($user->date_of_birth)
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                {{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years
                                                            </span>
                                                        @endif
                                                        @if($user->gender)
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 00-8 0m0 0v8a4 4 0 008 8v8a4 4 0 00-8 8z" />
                                                                </svg>
                                                                {{ ucfirst($user->gender) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($user->profile && $user->profile->apartment_location)
                                                        <div class="flex items-center mt-2 text-white/95 text-sm font-medium drop-shadow">
                                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            {{ $user->profile->apartment_location }}
                                                        </div>
                                                    @elseif($user->preferences && $user->preferences->preferred_location)
                                                        <div class="flex items-center mt-2 text-white/95 text-sm font-medium drop-shadow">
                                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            {{ $user->preferences->preferred_location }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                New
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Enhanced Profile Details -->
                                    <div class="p-6 card-content">
                                        <!-- Compatibility Score -->
                                        <div class="mb-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-semibold text-white">Compatibility</span>
                                                <span class="text-sm font-bold text-white">{{ rand(75, 95) }}%</span>
                                            </div>
                                            <div class="w-full bg-transparent rounded-full h-2 border border-white/30">
                                                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: {{ rand(75, 95) }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Quick Info -->
                                        <div class="space-y-3 mb-6">
                                            @if($user->profile && ($user->profile->budget_min && $user->profile->budget_max))
                                                <div class="flex items-center justify-between bg-transparent rounded-xl p-3 border border-white/20">
                                                    <span class="text-sm font-medium text-white">Budget Range</span>
                                                    <span class="text-sm font-bold text-white">₱{{ number_format($user->profile->budget_min ?? 0, 0) }} - ₱{{ number_format($user->profile->budget_max ?? 0) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($user->profile && $user->profile->university)
                                                <div class="flex items-center bg-transparent rounded-xl p-3 border border-white/20">
                                                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    <span class="text-sm text-white">{{ $user->profile->university }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Lifestyle Preferences -->
                                        @if($user->preferences)
                                            <div class="mb-6">
                                                <h4 class="text-sm font-semibold text-white mb-3">Lifestyle Preferences</h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($user->preferences->cleanliness_level)
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                                            {{ ucfirst(str_replace('_', ' ', $user->preferences->cleanliness_level)) }}
                                                        </span>
                                                    @endif
                                                    @if($user->preferences->sleep_pattern)
                                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                            {{ ucfirst(str_replace('_', ' ', $user->preferences->sleep_pattern)) }}
                                                        </span>
                                                    @endif
                                                    @if($user->preferences->smoking)
                                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                                            {{ ucfirst(str_replace('_', ' ', $user->preferences->smoking)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-3">
                                            <button onclick="likeUser({{ $user->id }})" 
                                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                                                Like
                                            </button>
                                            <button onclick="dislikeUser({{ $user->id }})" 
                                                    class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-gray-700 hover:to-gray-800 transition-all duration-200 shadow-lg">
                                                Pass
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="mx-auto h-20 w-20 text-white/60 mb-4">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-xl font-bold text-white">No users found</h3>
                                <p class="mt-1 text-sm text-white/80">
                                    No other users available at the moment.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Load More -->
                    @if(isset($users) && $users->hasMorePages())
                    <div class="text-center pb-8 bg-white/5">
                        <a href="{{ $users->nextPageUrl() }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition">
                            Load More Users
                        </a>
                    </div>
                    @endif

                    <!-- QUICK ACTIONS -->
                    <div class="border-t border-white/20 p-8 bg-white/10 backdrop-blur-sm">
                        <h4 class="text-sm font-semibold text-white/80 uppercase mb-6">
                            Quick Actions
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                            <!-- Find Roommates -->
                            <a href="{{ route('roommates.index') }}"
                               class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-6 flex items-center space-x-4 hover:bg-white/20 hover:shadow-lg hover:border-white/30 transition-all duration-200 transform hover:scale-105">
                                <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Find Roommates</p>
                                    <p class="text-xs text-white/70">Browse trusted matches</p>
                                </div>
                            </a>

                            <!-- List Your Space -->
                            <a href="{{ route('listings.create') }}"
                               class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-6 flex items-center space-x-4 hover:bg-white/20 hover:shadow-lg hover:border-white/30 transition-all duration-200 transform hover:scale-105">
                                <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10h14V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-white">List Your Room</p>
                                    <p class="text-xs text-white/70">Find your ideal roommate</p>
                                </div>
                            </a>

                            <!-- Messages -->
                            <a href="{{ route('messages.index') }}"
                               class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-6 flex items-center space-x-4 hover:bg-white/20 hover:shadow-lg hover:border-white/30 transition-all duration-200 transform hover:scale-105">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5V6h14v8h-5l-5 5V16"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Messages</p>
                                    <p class="text-xs text-white/70">Chat with roommates</p>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User action functions for activity page
        function likeUser(userId) {
            const card = document.querySelector(`[data-user-id="${userId}"]`);
            const actionButtons = card.querySelector('.flex.space-x-3');
            
            // Show loading state
            actionButtons.innerHTML = '<div class="flex-1 text-center"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div></div>';
            
            fetch(`/matches/${userId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_mutual) {
                        alert('It\'s a match! You can now message each other.');
                    } else {
                        alert('Like sent! Waiting for their response.');
                    }
                    // Remove the card with animation
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        // Check if no more cards
                        const remainingCards = document.querySelectorAll('.user-card');
                        if (remainingCards.length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert(data.message || 'Failed to like user');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                location.reload();
            });
        }
        
        function dislikeUser(userId) {
            const card = document.querySelector(`[data-user-id="${userId}"]`);
            const actionButtons = card.querySelector('.flex.space-x-3');
            
            // Show loading state
            actionButtons.innerHTML = '<div class="flex-1 text-center"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div></div>';
            
            fetch(`/matches/${userId}/dislike`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the card with animation
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        // Check if no more cards
                        const remainingCards = document.querySelectorAll('.user-card');
                        if (remainingCards.length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert(data.message || 'Failed to pass user');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                location.reload();
            });
        }

        // Filter modal functions
        function showFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
        }
        
        function closeFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
        }
        
        function resetFilters() {
            window.location.href = '{{ route("activity.index") }}';
        }
    </script>

    <style>
        /* Force transparency with highest specificity */
        div.user-card.group.rounded-2xl {
            background: transparent !important;
            background-color: transparent !important;
            backdrop-filter: none !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        .user-card {
            transition: all 0.3s ease;
            background: transparent !important;
            backdrop-filter: none !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Enhanced button styles for better visibility */
        .user-card button {
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .user-card button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        }
        
        /* Specific button enhancements */
        .user-card button[class*="from-indigo"] {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: 1px solid #4f46e5;
        }
        
        .user-card button[class*="from-gray"] {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: 1px solid #4b5563;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-card {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Enhanced profile details section */
        .user-card .card-content {
            background: transparent !important;
            backdrop-filter: none !important;
        }
        
        /* Force transparency on all backgrounds */
        .user-card * {
            background-color: transparent !important;
        }
        
        /* Only allow specific backgrounds */
        .user-card .bg-gradient-to-br,
        .user-card .bg-gradient-to-r,
        .user-card button {
            background-color: revert !important;
        }
        
        /* Override any white backgrounds */
        .user-card div[class*="bg-white"] {
            background: transparent !important;
            background-color: transparent !important;
        }
    </style>
</x-app-layout>
