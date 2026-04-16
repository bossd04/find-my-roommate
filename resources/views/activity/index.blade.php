@extends('layouts.app-with-sidebar')

@section('content')
    <!-- Hero Section with Background -->
    <div class="mb-8 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl hero-section"
         style="background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 50%, #EC4899 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-20 right-20 w-32 h-32 bg-blue-200 rounded-full blur-2xl animate-pulse delay-75"></div>
            <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-purple-200 rounded-full blur-3xl animate-pulse delay-100"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 bg-pink-200 rounded-full blur-xl animate-pulse delay-150"></div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Your Roommate Activity Feed 📊</h1>
            <p class="text-blue-100 text-lg">Track your latest interactions and updates in real-time</p>
        </div>
    </div>
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

    <!-- PAGE BODY -->
    <div class="min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/30 transform transition-all duration-300 hover:shadow-2xl">

                <!-- TITLE AREA -->
                <div class="p-8 border-b border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Activity Feed</h3>
                            <p class="text-sm text-purple-600">Your latest interactions and updates</p>
                        </div>

                            <div class="flex space-x-3 mt-4 sm:mt-0">
                                <button onclick="showFilterModal()" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white border border-indigo-200 rounded-lg shadow-sm text-sm font-medium hover:from-indigo-600 hover:to-purple-600 transition-all duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                    @if(request('type') || request('search'))
                                        <span class="ml-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-white text-xs font-medium text-indigo-600">
                                            {{ (request('type') ? 1 : 0) + (request('search') ? 1 : 0) }}
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIVITIES LIST -->
                    <div class="p-6 bg-gradient-to-br from-blue-50/30 to-indigo-50/30">
                        @if($activities->isEmpty())
                            <div class="text-center py-12 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl">
                                <svg class="mx-auto h-12 w-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">No activities found</h3>
                                <p class="mt-1 text-sm text-purple-500">
                                    @if(request('type') || request('search'))
                                        Try adjusting your search or filter to find what you're looking for.
                                    @else
                                        You don't have any activities yet.
                                    @endif
                                </p>
                                @if(request('type') || request('search'))
                                    <div class="mt-6">
                                        <a href="{{ route('activity.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                    <div class="border-t border-blue-200 p-8 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
                        <h4 class="text-sm font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent uppercase mb-6">
                            Quick Actions
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            <!-- Find Roommates -->
                            <a href="{{ route('roommates.index') }}"
                               class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 flex items-center space-x-3 hover:shadow-md hover:border-blue-400 transition-all duration-200 hover:-translate-y-0.5">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Find Roommates</p>
                                    <p class="text-xs text-purple-500">Browse trusted matches</p>
                                </div>
                            </a>

                            <!-- Messages -->
                            <a href="{{ route('messages.index') }}"
                               class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 flex items-center space-x-3 hover:shadow-md hover:border-blue-400 transition-all duration-200 hover:-translate-y-0.5">
                                <div class="p-2 bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5V6h14v8h-5l-5 5V16"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Messages</p>
                                    <p class="text-xs text-purple-500">Chat with roommates</p>
                                </div>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }

    function resetFilters() {
        document.getElementById('filter-form').reset();
        window.location.href = '{{ route('activity.index') }}';
    }

    // Close modal when clicking outside
    document.getElementById('filterModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFilterModal();
        }
    });
</script>
@endpush
