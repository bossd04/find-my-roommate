@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if(!auth()->user()->profile)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Please complete your profile to start finding roommates!
                                    <a href="{{ route('profiles.create') }}" class="font-medium text-blue-700 hover:text-blue-600 underline">Complete Profile</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Welcome, {{ auth()->user()->name }}!</h2>
                
                @if(auth()->user()->profile)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Profile Summary</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ auth()->user()->profile->display_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ auth()->user()->profile->university ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Living Preferences</p>
                                    <p class="text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', auth()->user()->profile->cleanliness_level)) }} • 
                                        {{ ucfirst(str_replace('_', ' ', auth()->user()->profile->noise_level)) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Schedule</p>
                                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', auth()->user()->profile->schedule)) }}</p>
                                </div>
                                @if(auth()->user()->profile->has_apartment && auth()->user()->profile->apartment_location)
                                    <div class="sm:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Apartment Location</p>
                                        <p class="text-sm text-gray-900">{{ auth()->user()->profile->apartment_location }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Your Matches</h3>
                            <a href="{{ route('matches') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all matches</a>
                        </div>
                        
                        @if($matches->count() > 0)
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($matches->take(3) as $match)
                                    <div class="bg-white overflow-hidden shadow rounded-lg">
                                        <div class="px-4 py-5 sm:p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-medium">{{ strtoupper(substr($match['user']->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $match['user']->profile->display_name }}</h4>
                                                    <div class="flex items-center">
                                                        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                                            <div class="h-full bg-green-500" style="width: {{ $match['score'] }}%"></div>
                                                        </div>
                                                        <span class="ml-2 text-sm font-medium text-gray-500">{{ $match['score'] }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-sm text-gray-600 line-clamp-2">{{ $match['user']->profile->bio ?? 'No bio available' }}</p>
                                            </div>
                                            <div class="mt-4 flex justify-end">
                                                <a href="{{ route('profiles.show', $match['user']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No matches yet</h3>
                                <p class="mt-1 text-sm text-gray-500">We're still looking for potential roommates that match your preferences.</p>
                                <div class="mt-6">
                                    <a href="{{ route('profiles.edit', auth()->user()->profile) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Update Preferences
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Find Your Perfect Roommate</h3>
                        <p class="mt-1 text-sm text-gray-500">Connect with potential roommates based on your lifestyle and preferences.</p>
                        <div class="mt-6">
                            @if(auth()->user()->profile)
                                <a href="{{ route('matches') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Browse Matches
                                </a>
                            @else
                                <a href="{{ route('profiles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create Your Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
