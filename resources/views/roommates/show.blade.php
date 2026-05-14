@extends('layouts.app-with-sidebar')

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('roommates.index') }}" class="inline-flex items-center text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Roommates
        </a>
    </div>

    <!-- Hero Section with Profile Header -->
    <div class="mb-6 rounded-2xl p-5 md:p-6 text-white relative overflow-hidden shadow-xl" 
         style="background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-indigo-200 rounded-full blur-3xl animate-pulse"></div>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-center gap-4 md:gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($roommate->avatar_url || $roommate->profile_photo_url)
                    <img src="{{ $roommate->avatar_url ?? $roommate->profile_photo_url }}" 
                         alt="{{ $roommate->first_name }} {{ $roommate->last_name }}" 
                         class="h-24 w-24 md:h-28 md:w-28 rounded-full object-cover border-4 border-white border-opacity-20 shadow-lg">
                @else
                    <div class="h-24 w-24 md:h-28 md:w-28 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-3xl font-bold border-4 border-white border-opacity-20">
                        {{ strtoupper(substr($roommate->first_name, 0, 1)) }}{{ strtoupper(substr($roommate->last_name, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1">
                    <h1 class="text-2xl md:text-3xl font-bold">{{ $roommate->first_name }} {{ $roommate->last_name }}</h1>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        <!-- Verification Badge -->
                        @if($roommate->isVerified())
                            <span class="inline-flex items-center bg-green-500 bg-opacity-90 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z" clip-rule="evenodd" />
                                </svg>
                                Verified
                            </span>
                        @endif
                        
                        <!-- Looking for Roommate Badge -->
                        @if($roommate->looking_for_roommate)
                            <span class="inline-flex items-center bg-pink-500 bg-opacity-95 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Looking for Roommate
                            </span>
                        @endif
                    </div>
                </div>
                
                <p class="text-blue-100 text-sm md:text-base opacity-90 mb-2">{{ $roommate->university ?? 'Student' }} • {{ $roommate->email }}</p>
                
                <!-- Location Info -->
                @if($roommate->profile && ($roommate->profile->apartment_location || $roommate->profile->city))
                    <div class="flex items-center justify-center md:justify-start text-xs text-blue-100 opacity-80">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3.0 11-6 0 3 3.0 016 0z" />
                        </svg>
                        {{ $roommate->profile->apartment_location ?? $roommate->profile->city }}
                    </div>
                @endif
            </div>
            
            <!-- Compatibility Score -->
            @if(isset($roommate->compatibility_score))
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-3 text-center min-w-[120px] border border-white border-opacity-10">
                    <div class="text-[10px] uppercase font-bold text-blue-100 tracking-wider mb-0.5">Compatibility</div>
                    <div class="text-2xl font-black">{{ $roommate->compatibility_score }}%</div>
                    <div class="w-full bg-white bg-opacity-20 rounded-full h-1.5 mt-1.5">
                        @php
                            $color = $roommate->compatibility_score >= 80 ? 'bg-green-400' : ($roommate->compatibility_score >= 60 ? 'bg-blue-400' : 'bg-yellow-400');
                        @endphp
                        <div class="{{ $color }} h-1.5 rounded-full" style="width: {{ $roommate->compatibility_score }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- About Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    About
                </h2>
                
                @if($roommate->profile && $roommate->profile->bio)
                    <p class="text-gray-900 leading-relaxed">{{ $roommate->profile->bio }}</p>
                @else
                    <p class="text-gray-700 italic">No bio available</p>
                @endif
                
                <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @if($roommate->gender)
                        <div class="bg-gray-100 rounded-lg p-3">
                            <div class="text-xs text-gray-800 uppercase font-bold">Gender</div>
                            <div class="font-semibold text-gray-900">{{ $roommate->gender }}</div>
                        </div>
                    @endif
                    @if($roommate->date_of_birth)
                        <div class="bg-gray-100 rounded-lg p-3">
                            <div class="text-xs text-gray-800 uppercase font-bold">Age</div>
                            <div class="font-semibold text-gray-900">{{ $roommate->age }} years</div>
                        </div>
                    @endif
                    @if($roommate->year_level)
                        <div class="bg-gray-100 rounded-lg p-3">
                            <div class="text-xs text-gray-800 uppercase font-bold">Year Level</div>
                            <div class="font-semibold text-gray-900">{{ $roommate->year_level }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Education Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.083 12.083 0 01.665-6.479L12 14z" />
                    </svg>
                    Education
                </h2>
                <div class="space-y-3">
                    @if($roommate->university)
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $roommate->university }}</div>
                                <div class="text-sm text-gray-700">University</div>
                            </div>
                        </div>
                    @endif
                    @if($roommate->course)
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $roommate->course }}</div>
                                <div class="text-sm text-gray-700">Course</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preferences Section -->
            @if($roommate->preferences)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Personal Preferences
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($roommate->preferences->lifestyle)
                            <div class="bg-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-800 uppercase font-bold">Lifestyle</div>
                                <div class="font-semibold text-gray-900">{{ $roommate->preferences->lifestyle }}</div>
                            </div>
                        @endif
                        @if($roommate->preferences->hobbies)
                            <div class="bg-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-800 uppercase font-bold">Hobbies</div>
                                <div class="font-semibold text-gray-900">{{ $roommate->preferences->hobbies }}</div>
                            </div>
                        @endif
                        @if($roommate->preferences->cleanliness)
                            <div class="bg-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-800 uppercase font-bold">Cleanliness</div>
                                <div class="font-semibold text-gray-900">{{ $roommate->preferences->cleanliness }}</div>
                            </div>
                        @endif
                        @if($roommate->preferences->smoking)
                            <div class="bg-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-800 uppercase font-bold">Smoking</div>
                                <div class="font-semibold text-gray-900">{{ $roommate->preferences->smoking }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif



            <!-- What They're Looking For (Roommate Preferences) -->
            @if($roommate->looking_for_roommate && $roommate->roommatePreference)
                @php
                    $prefs = $roommate->roommatePreference;
                @endphp
                <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-pink-500">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        What They're Looking For
                    </h2>
                    <p class="text-sm text-gray-700 mb-4">This user is looking for a roommate with these preferences:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($prefs->preferred_gender && $prefs->preferred_gender != 'no_preference')
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Preferred Gender</div>
                                <div class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $prefs->preferred_gender) }}</div>
                            </div>
                        @endif
                        
                        @if($prefs->min_age || $prefs->max_age)
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Preferred Age</div>
                                <div class="font-bold text-gray-900">{{ $prefs->min_age }} - {{ $prefs->max_age == 120 ? 'Any' : $prefs->max_age }} years</div>
                            </div>
                        @endif
                        
                        @if($prefs->preferred_cleanliness && $prefs->preferred_cleanliness != 'no_preference')
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Preferred Cleanliness</div>
                                <div class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $prefs->preferred_cleanliness) }}</div>
                            </div>
                        @endif
                        
                        @if($prefs->preferred_schedule && $prefs->preferred_schedule != 'no_preference')
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Preferred Schedule</div>
                                <div class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $prefs->preferred_schedule) }}</div>
                            </div>
                        @endif
                        
                        @if($prefs->preferred_location)
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Preferred Location</div>
                                <div class="font-bold text-gray-900">{{ $prefs->preferred_location }}</div>
                            </div>
                        @endif
                        
                        @if($prefs->preferred_lease_duration && $prefs->preferred_lease_duration != 'no_preference')
                            <div class="bg-pink-100 rounded-lg p-3">
                                <div class="text-xs text-pink-800 uppercase font-bold">Lease Duration</div>
                                <div class="font-bold text-gray-900">{{ $prefs->preferred_lease_duration }}</div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 flex flex-wrap gap-3">
                        @if($prefs->smoking_ok)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-900">
                                <svg class="w-4 h-4 mr-1 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Smoking OK
                            </span>
                        @endif
                        
                        @if($prefs->pets_ok)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-900">
                                <svg class="w-4 h-4 mr-1 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Pets OK
                            </span>
                        @endif
                        
                        @if($prefs->has_apartment_preferred)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-900">
                                <svg class="w-4 h-4 mr-1 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Has Apartment Preferred
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions & Budget -->
        <div class="space-y-6">
            <!-- Budget Info -->
            @if($roommate->profile && ($roommate->profile->budget_min || $roommate->profile->budget_max))
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Budget Range
                    </h3>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ number_format($roommate->profile->budget_min ?? 0) }} - {{ number_format($roommate->profile->budget_max ?? 0) }}
                    </div>
                    <div class="text-sm text-gray-700 mt-1">per month</div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                
                @if($roommate->match_status === 'accepted' || $roommate->is_mutual_match)
                    <!-- Message Button for matched users -->
                    <a href="{{ route('messages.show', $roommate) }}" class="w-full mb-3 inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Message
                    </a>
                    <div class="text-center text-sm text-green-700 font-medium">
                        <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z" clip-rule="evenodd" />
                        </svg>
                        It's a Match!
                    </div>
                @elseif($roommate->match_status === 'pending')
                    <div class="w-full px-4 py-3 bg-yellow-200 text-yellow-900 rounded-xl text-center font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pending Match
                    </div>
                @else
                    <!-- Like Button -->
                    <form action="{{ route('matches.like', $roommate) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                            Like
                        </button>
                    </form>
                    
                    <!-- Dislike Button -->
                    <form action="{{ route('matches.dislike', $roommate) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Pass
                        </button>
                    </form>
                @endif
            </div>

            <!-- Report/Block -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-3">Safety</h3>
                <div class="space-y-2">
                    <form action="{{ route('users.report', $roommate) }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-800 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Report User
                        </button>
                    </form>
                    <form action="{{ route('users.block', $roommate) }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-800 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Block User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
