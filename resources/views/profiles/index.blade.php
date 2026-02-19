@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            My Profile
                        </h2>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4
                    ">
                        <a href="{{ route('profiles.edit', auth()->user()->profile) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                            <p class="mt-1 text-sm text-gray-500">This information will be displayed to potential roommates.</p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="space-y-6">
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                    <div class="px-4 py-5 sm:px-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $profile->display_name }}</h3>
                                                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $profile->age }} years old • {{ ucfirst($profile->gender) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                                        <dl class="sm:divide-y sm:divide-gray-200">
                                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">About</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    {{ $profile->bio ?? 'No bio provided' }}
                                                </dd>
                                            </div>
                                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">Education</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    @if($profile->university || $profile->major)
                                                        {{ $profile->university }} @if($profile->major) • {{ $profile->major }} @endif
                                                    @else
                                                        Not specified
                                                    @endif
                                                </dd>
                                            </div>
                                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">Living Preferences</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    <div class="space-y-2">
                                                        <div>
                                                            <span class="font-medium">Cleanliness:</span> 
                                                            <span class="capitalize">{{ str_replace('_', ' ', $profile->cleanliness_level) }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium">Noise Level:</span> 
                                                            <span class="capitalize">{{ str_replace('_', ' ', $profile->noise_level) }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium">Schedule:</span> 
                                                            <span class="capitalize">{{ str_replace('_', ' ', $profile->schedule) }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium">Smoking:</span> 
                                                            {{ $profile->smoking_allowed ? 'Allowed' : 'Not allowed' }}
                                                        </div>
                                                        <div>
                                                            <span class="font-medium">Pets:</span> 
                                                            {{ $profile->pets_allowed ? 'Allowed' : 'Not allowed' }}
                                                        </div>
                                                    </div>
                                                </dd>
                                            </div>
                                            @if($profile->has_apartment && $profile->apartment_location)
                                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                    <dt class="text-sm font-medium text-gray-500">Apartment</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                        {{ $profile->apartment_location }}
                                                        @if($profile->budget_min || $profile->budget_max)
                                                            <div class="mt-1">
                                                                <span class="font-medium">Budget:</span> 
                                                                @if($profile->budget_min && $profile->budget_max)
                                                                    ${{ number_format($profile->budget_min) }} - ${{ number_format($profile->budget_max) }}
                                                                @elseif($profile->budget_min)
                                                                    From ${{ number_format($profile->budget_min) }}
                                                                @elseif($profile->budget_max)
                                                                    Up to ${{ number_format($profile->budget_max) }}
                                                                @endif
                                                                / month
                                                            </div>
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endif
                                            @if($profile->move_in_date || $profile->lease_duration)
                                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                    <dt class="text-sm font-medium text-gray-500">Timing</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                        @if($profile->move_in_date)
                                                            <div>Move-in: {{ $profile->move_in_date }}</div>
                                                        @endif
                                                        @if($profile->lease_duration)
                                                            <div>Lease Duration: {{ $profile->lease_duration }}</div>
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endif
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Roommate Preferences</h3>
                            <p class="mt-1 text-sm text-gray-500">Your preferences for potential roommates.</p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                @if($preferences)
                                    <div class="px-4 py-5 sm:px-6">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">Your Ideal Roommate</h3>
                                    </div>
                                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                                        <dl class="sm:divide-y sm:divide-gray-200">
                                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    {{ $preferences->preferred_gender === 'no_preference' ? 'No preference' : ucfirst($preferences->preferred_gender) }}
                                                </dd>
                                            </div>
                                            @if($preferences->min_age || $preferences->max_age)
                                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                    <dt class="text-sm font-medium text-gray-500">Age Range</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                        @if($preferences->min_age && $preferences->max_age)
                                                            {{ $preferences->min_age }} - {{ $preferences->max_age }} years old
                                                        @elseif($preferences->min_age)
                                                            {{ $preferences->min_age }}+ years old
                                                        @elseif($preferences->max_age)
                                                            Up to {{ $preferences->max_age }} years old
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endif
                                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">Living Preferences</dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    <div class="space-y-2">
                                                        @isset($preferences->preferred_cleanliness)
                                                        <div>
                                                            <span class="font-medium">Cleanliness:</span> 
                                                            <span class="capitalize">{{ $preferences->preferred_cleanliness === 'no_preference' ? 'No preference' : str_replace('_', ' ', $preferences->preferred_cleanliness) }}</span>
                                                        </div>
                                                        @endisset
                                                        @isset($preferences->preferred_noise_level)
                                                        <div>
                                                            <span class="font-medium">Noise Level:</span> 
                                                            <span class="capitalize">{{ $preferences->preferred_noise_level === 'no_preference' ? 'No preference' : str_replace('_', ' ', $preferences->preferred_noise_level) }}</span>
                                                        </div>
                                                        @endisset
                                                        @isset($preferences->preferred_schedule)
                                                        <div>
                                                            <span class="font-medium">Schedule:</span> 
                                                            <span class="capitalize">{{ $preferences->preferred_schedule === 'no_preference' ? 'No preference' : str_replace('_', ' ', $preferences->preferred_schedule) }}</span>
                                                        </div>
                                                        @endisset
                                                        @isset($preferences->smoking_ok)
                                                        <div>
                                                            <span class="font-medium">Smoking:</span> 
                                                            {{ $preferences->smoking_ok ? 'Allowed' : 'Not allowed' }}
                                                        </div>
                                                        @endisset
                                                        @isset($preferences->pets_ok)
                                                        <div>
                                                            <span class="font-medium">Pets:</span> 
                                                            {{ $preferences->pets_ok ? 'Allowed' : 'Not allowed' }}
                                                        </div>
                                                        @endisset
                                                    </div>
                                                </dd>
                                            </div>
                                            @if(!is_null($preferences->has_apartment_preferred) || $preferences->preferred_location)
                                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                    <dt class="text-sm font-medium text-gray-500">Housing</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                        @if(!is_null($preferences->has_apartment_preferred))
                                                            <div>
                                                                <span class="font-medium">Has Apartment:</span> 
                                                                {{ $preferences->has_apartment_preferred ? 'Yes' : 'No' }}
                                                            </div>
                                                        @endif
                                                        @if($preferences->preferred_location)
                                                            <div class="mt-1">
                                                                <span class="font-medium">Preferred Location:</span> 
                                                                {{ $preferences->preferred_location }}
                                                            </div>
                                                        @endif
                                                        @if($preferences->min_budget || $preferences->max_budget)
                                                            <div class="mt-1">
                                                                <span class="font-medium">Budget Range:</span> 
                                                                @if($preferences->min_budget && $preferences->max_budget)
                                                                    ${{ number_format($preferences->min_budget) }} - ${{ number_format($preferences->max_budget) }}
                                                                @elseif($preferences->min_budget)
                                                                    From ${{ number_format($preferences->min_budget) }}
                                                                @elseif($preferences->max_budget)
                                                                    Up to ${{ number_format($preferences->max_budget) }}
                                                                @endif
                                                                / month
                                                            </div>
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endif
                                            @if($preferences->preferred_move_in_date || $preferences->preferred_lease_duration || $preferences->willing_to_share_room)
                                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                    <dt class="text-sm font-medium text-gray-500">Additional Preferences</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                        @if($preferences->preferred_move_in_date)
                                                            <div>Preferred Move-in: {{ $preferences->preferred_move_in_date }}</div>
                                                        @endif
                                                        @if($preferences->preferred_lease_duration)
                                                            <div>Preferred Lease Duration: {{ $preferences->preferred_lease_duration }}</div>
                                                        @endif
                                                        @if($preferences->willing_to_share_room)
                                                            <div>Willing to share a room</div>
                                                        @endif
                                                        @if(!is_null($preferences->furnished_preferred))
                                                            <div>Furnished: {{ $preferences->furnished_preferred ? 'Yes' : 'No' }}</div>
                                                        @endif
                                                        @if(!is_null($preferences->utilities_included_preferred))
                                                            <div>Utilities Included: {{ $preferences->utilities_included_preferred ? 'Yes' : 'No' }}</div>
                                                        @endif
                                                        @if($preferences->preferred_room_type)
                                                            <div>Room Type: {{ ucfirst(str_replace('_', ' ', $preferences->preferred_room_type)) }}</div>
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endif
                                        </dl>
                                    </div>
                                @else
                                    <div class="px-4 py-5 sm:p-6 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No preferences set</h3>
                                        <p class="mt-1 text-sm text-gray-500">Set your roommate preferences to find better matches.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('preferences.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Set Preferences
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
