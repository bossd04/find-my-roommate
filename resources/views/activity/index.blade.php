<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="p-2 bg-white/80 hover:bg-white/90 backdrop-blur-md rounded-xl shadow-lg mr-3 border border-white/20 transition-colors duration-200">
                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </a>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
                    Your Roommate Activity Feed
                </h2>
            </div>
            <div class="flex items-center">
                <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center space-x-2 bg-white/80 hover:bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/20 transition-colors duration-200">
                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Background Theme with Dorm/Boarding House -->
    <div class="fixed inset-0 bg-gradient-to-br from-indigo-50 to-blue-50 -z-10">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=1950&q=80')] bg-cover bg-center opacity-10"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagonal-striped-brick.png')] opacity-5"></div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
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
                                <select name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
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
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
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
    </div>

    <div class="relative z-10 min-h-screen">
        <!-- HEADER -->
        <!-- Custom Header -->
<header class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="p-2 bg-white/80 hover:bg-white/90 backdrop-blur-md rounded-xl shadow-lg mr-3 border border-white/20 transition-colors duration-200">
                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </a>

                <div class="flex-shrink-0">
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
                        Your Roommate Activity Feed
                    </h2>
                </div>
            </div>

            <div class="flex items-center">
                <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center space-x-2 bg-white/80 hover:bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/20 transition-colors duration-200">
                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </div>
    </div>
</header>

        <!-- PAGE BODY -->
        <div class="min-h-screen py-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/30 transform transition-all duration-300 hover:shadow-2xl">

                    <!-- TITLE AREA -->
                    <div class="p-8 border-b border-gray-200/50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Activity Feed</h3>
                                <p class="text-sm text-gray-600">Your latest interactions and updates</p>
                            </div>

                            <div class="flex space-x-3 mt-4 sm:mt-0">
                                <button onclick="showFilterModal()" class="px-4 py-2 bg-white border border-indigo-100 rounded-lg shadow-sm text-sm font-medium text-indigo-700 hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                    @if(request('type') || request('search'))
                                        <span class="ml-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-xs font-medium text-indigo-700">
                                            {{ (request('type') ? 1 : 0) + (request('search') ? 1 : 0) }}
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIVITIES LIST -->
                    <div class="p-6 bg-white/30">
                        @if($activities->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No activities found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if(request('type') || request('search'))
                                        Try adjusting your search or filter to find what you're looking for.
                                    @else
                                        You don't have any activities yet.
                                    @endif
                                </p>
                                @if(request('type') || request('search'))
                                    <div class="mt-6">
                                        <a href="{{ route('activity.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Clear all filters
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <ul class="-mb-8" id="activities-list">
                                @include('activity.partials.activities', ['activities' => $activities])
                            </ul>
                        @endif
                    </div>

                    <!-- Load More -->
                    @if($activities->hasMorePages())
                    <div class="text-center pb-8">
                        <button id="load-more" data-next-page="{{ $activities->nextPageUrl() }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition">
                            Load More Activities
                        </button>
                    </div>
                    @endif

                    <!-- QUICK ACTIONS -->
                    <div class="border-t border-gray-100 p-8 bg-indigo-50/50">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-4">
                            Quick Actions
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                            <!-- Find Roommates -->
                            <a href="{{ route('roommates.index') }}"
                               class="bg-white border rounded-xl p-4 flex items-center space-x-3 hover:shadow-md hover:border-indigo-200 transition">
                                <div class="p-2 bg-indigo-100 rounded-lg">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Find Roommates</p>
                                    <p class="text-xs text-gray-500">Browse trusted matches</p>
                                </div>
                            </a>

                            <!-- List Your Space -->
                            <a href="{{ route('listings.create') }}"
                               class="bg-white border rounded-xl p-4 flex items-center space-x-3 hover:shadow-md hover:border-indigo-200 transition">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10h14V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">List Your Room</p>
                                    <p class="text-xs text-gray-500">Find your ideal roommate</p>
                                </div>
                            </a>

                            <!-- Messages -->
                            <a href="{{ route('messages.index') }}"
                               class="bg-white border rounded-xl p-4 flex items-center space-x-3 hover:shadow-md hover:border-indigo-200 transition">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5V6h14v8h-5l-5 5V16"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Messages</p>
                                    <p class="text-xs text-gray-500">Chat with roommates</p>
                                </div>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
