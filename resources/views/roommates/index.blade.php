<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Roommates') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Find Your Perfect Roommate</h1>
                <p class="text-gray-600">Browse and connect with compatible roommates based on shared preferences and lifestyle.</p>
            </div>
            
            <!-- Simple Search Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <form method="GET" action="{{ route('roommates.index') }}" class="flex gap-4">
                    <input type="text" name="search" placeholder="Search by name..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                           value="{{ request('search') }}">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Search
                    </button>
                </form>
            </div>
                    
            <!-- Simple User Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($users as $user)
                    @if($user->id !== auth()->id())  <!-- Don't show current user -->
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-6 text-center">
                            <!-- Avatar -->
                            <div class="mb-4">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-gray-200">
                                @elseif($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-20 h-20 rounded-full mx-auto bg-gray-200 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Name and Basic Info -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $user->name }}</h3>
                            
                            @if($user->profile && $user->profile->university)
                                <p class="text-sm text-gray-600 mb-3">{{ $user->profile->university }}</p>
                            @endif
                            
                            <!-- Quick Info -->
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                @if($user->profile && ($user->profile->budget_min && $user->profile->budget_max))
                                    <p>Budget: ₱{{ number_format($user->profile->budget_min) }} - ₱{{ number_format($user->profile->budget_max) }}</p>
                                @endif
                                
                                @if($user->preferences && $user->preferences->lifestyle)
                                    <p>Lifestyle: {{ $user->preferences->lifestyle }}</p>
                                @endif
                                
                                @if($user->profile && $user->profile->cleanliness_level)
                                    <p>Cleanliness: {{ ucfirst(str_replace('_', ' ', $user->profile->cleanliness_level)) }}</p>
                                @endif
                            </div>
                            
                            <!-- View Profile Button -->
                            <a href="{{ route('profile.show', $user) }}" 
                               class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                View Profile
                            </a>
                        </div>
                    @endif
                @empty
                    <!-- Empty State -->
                    <div class="col-span-full">
                        <div class="text-center py-16 bg-white rounded-lg shadow-sm">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No roommates found</h3>
                            <p class="mt-2 text-sm text-gray-500">We couldn't find any roommates matching your search.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
