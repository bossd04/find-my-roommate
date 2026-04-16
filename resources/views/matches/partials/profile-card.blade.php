<div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-gray-700">
    <!-- Profile Header -->
    <div class="relative">
        {{-- Header Background --}}
        @php
            $headerBgUrl = $user->avatar_url ?? $user->profile_photo_url;
            $unsplashSeed = ($user->id ?? 0) + ($loop->index ?? 0);
        @endphp
        <div class="h-48 w-full bg-gradient-to-br from-blue-900 via-gray-800 to-gray-900 relative">
            @if($headerBgUrl)
                <img class="h-full w-full object-cover absolute inset-0 opacity-50" 
                     src="{{ $headerBgUrl }}" 
                     alt="" 
                     onerror="this.style.display='none';">
            @else
                <div class="h-full w-full bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 opacity-80"></div>
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
        <div class="absolute bottom-4 left-4">
            <div class="flex items-center">
                @php
                    $avatarUrl = $user->avatar_url ?? $user->profile_photo_url;
                    $initial = strtoupper(substr($user->first_name, 0, 1));
                @endphp
                <div class="h-14 w-14 rounded-full border-2 border-blue-500 bg-gray-700 flex items-center justify-center overflow-hidden relative shrink-0">
                    {{-- Initials always present as fallback (centered) --}}
                    <span class="text-xl font-bold text-blue-400">{{ $initial }}</span>
                    {{-- Image overlays initials when available --}}
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" 
                             alt="" 
                             class="h-full w-full object-cover absolute inset-0 profile-avatar-img"
                             onerror="this.remove();">
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-bold text-lg">{{ $user->fullName() ?? $user->name }}</h3>
                    @if($user->age)
                    <p class="text-gray-300 text-sm">{{ abs($user->age) }} years old</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Compatibility Score Badge -->
        <div class="absolute top-4 left-4">
            @php
                $compatibilityScore = $user->compatibility_score ?? 0;
                $interactionData = $user->interaction_data ?? [];
                $interactionCount = $interactionData['interaction_count'] ?? 0;
                $profileViews = $interactionData['profile_views'] ?? 0;
                $messagesExchanged = $interactionData['messages_exchanged'] ?? 0;
                $isFullyCompatible = $interactionData['is_fully_compatible'] ?? false;
                
                $scoreColor = $compatibilityScore >= 80 ? 'bg-green-500' : 
                            ($compatibilityScore >= 60 ? 'bg-blue-500' : 
                            ($compatibilityScore >= 40 ? 'bg-yellow-500' : 'bg-red-500'));
            @endphp
            <div class="flex flex-col space-y-1">
                <div class="flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $scoreColor }} bg-opacity-90 text-white shadow-lg border border-white border-opacity-20">
                    @if($isFullyCompatible)
                        <svg class="w-4 h-4 mr-1 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    {{ $compatibilityScore }}% Match
                    @if($isFullyCompatible)
                        <span class="ml-1 text-xs">Perfect!</span>
                    @endif
                </div>
                
                @if($interactionCount > 0)
                    <div class="flex items-center justify-center text-[10px] text-gray-300 space-x-2 bg-black bg-opacity-30 rounded-full py-0.5 px-2">
                        @if($profileViews > 0)
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ $profileViews }}
                            </span>
                        @endif
                        @if($messagesExchanged > 0)
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ $messagesExchanged }}
                            </span>
                        @endif
                    </div>
                @endif
                
                <!-- Match Progress Bar -->
                <div class="w-full bg-gray-700 bg-opacity-50 rounded-full h-1.5 mt-1 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 h-full rounded-full transition-all duration-500" 
                         style="width: {{ $compatibilityScore }}%"></div>
                </div>
            </div>
        </div>
        
        <!-- Looking for Roommate Badge -->
        <div class="absolute top-4 right-4">
            @if($user->looking_for_roommate)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-500 bg-opacity-90 text-white shadow-lg">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    Looking
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-600 bg-opacity-80 text-gray-300 shadow-lg">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Not Looking
                </span>
            @endif
        </div>
    </div>

    <!-- Profile Details -->
    <div class="p-4 sm:p-6">
        <!-- About Section -->
        <div class="mt-1 sm:mt-2">
            <h5 class="text-xs sm:text-sm font-medium text-gray-300 mb-1.5 sm:mb-2">About</h5>
            
            <!-- Bio -->
            @if($user->bio || ($user->profile && $user->profile->bio))
            <p class="text-xs sm:text-sm text-gray-400 mb-2 sm:mb-3 line-clamp-2">
                {{ $user->bio ?? $user->profile->bio }}
            </p>
            @endif
            
            <div class="space-y-1 sm:space-y-2">
                <!-- Gender -->
                @if($user->gender)
                <div class="flex items-center text-xs sm:text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ ucfirst($user->gender) }}</span>
                </div>
                @endif
                
                <!-- University & Course -->
                @if($user->university || $user->course)
                <div class="flex items-center text-xs sm:text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    <span class="truncate">{{ $user->university }} {{ $user->course ? ' - ' . $user->course : '' }}</span>
                </div>
                @endif
                
                <!-- Location -->
                @if($user->location || ($user->profile && $user->profile->apartment_location))
                <div class="flex items-center text-xs sm:text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $user->location ?? $user->profile->apartment_location }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Lifestyle Preferences -->
        @if($user->profile && ($user->profile->cleanliness_level || $user->profile->sleep_pattern || $user->profile->study_habit))
        <div class="mt-3 sm:mt-4">
            <h5 class="text-xs sm:text-sm font-medium text-gray-300 mb-1.5 sm:mb-2">Lifestyle</h5>
            <div class="flex flex-wrap gap-1.5 sm:gap-2">
                @if($user->profile->cleanliness_level)
                <span class="px-2 py-0.5 sm:py-1 bg-blue-600 bg-opacity-30 rounded-full text-[10px] sm:text-xs text-blue-200 border border-blue-500 border-opacity-30">{{ ucfirst(str_replace('_', ' ', $user->profile->cleanliness_level)) }}</span>
                @endif
                @if($user->profile->sleep_pattern)
                <span class="px-2 py-0.5 sm:py-1 bg-blue-600 bg-opacity-30 rounded-full text-[10px] sm:text-xs text-blue-200 border border-blue-500 border-opacity-30">{{ ucfirst(str_replace('_', ' ', $user->profile->sleep_pattern)) }}</span>
                @endif
                @if($user->profile->study_habit)
                <span class="px-2 py-0.5 sm:py-1 bg-blue-600 bg-opacity-30 rounded-full text-[10px] sm:text-xs text-blue-200 border border-blue-500 border-opacity-30">{{ ucfirst(str_replace('_', ' ', $user->profile->study_habit)) }}</span>
                @endif
                @if($user->profile->noise_tolerance)
                <span class="px-2 py-0.5 sm:py-1 bg-blue-600 bg-opacity-30 rounded-full text-[10px] sm:text-xs text-blue-200 border border-blue-500 border-opacity-30">{{ ucfirst(str_replace('_', ' ', $user->profile->noise_tolerance)) }}</span>
                @endif
            </div>
        </div>
        @endif

        <!-- Hobbies -->
        @if($user->hobbies || ($user->profile && $user->profile->hobbies))
        @php
            $hobbies = $user->hobbies ?? $user->profile->hobbies;
            if (is_string($hobbies)) {
                $hobbies = json_decode($hobbies, true) ?? [];
            }
        @endphp
        @if(is_array($hobbies) && count($hobbies) > 0)
        <div class="mt-3 sm:mt-4">
            <h5 class="text-xs sm:text-sm font-medium text-gray-300 mb-1.5 sm:mb-2">Hobbies</h5>
            <div class="flex flex-wrap gap-1.5 sm:gap-2">
                @foreach(array_slice($hobbies, 0, 3) as $hobby)
                <span class="px-2 py-0.5 sm:py-1 bg-indigo-600 bg-opacity-40 rounded-full text-[10px] sm:text-xs text-indigo-200 border border-indigo-500 border-opacity-30">{{ $hobby }}</span>
                @endforeach
                @if(count($hobbies) > 3)
                <span class="px-2 py-0.5 sm:py-1 bg-indigo-600 bg-opacity-20 rounded-full text-[10px] sm:text-xs text-indigo-300">+{{ count($hobbies) - 3 }}</span>
                @endif
            </div>
        </div>
        @endif
        @endif



        <!-- Budget & Preferences -->
        <div class="mt-4">
            <h5 class="text-sm font-medium text-gray-300 mb-2">Preferences</h5>
            <div class="space-y-2">
                @if($user->preferences && $user->preferences->budget_min && $user->preferences->budget_max)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Budget: ₱{{ number_format($user->preferences->budget_min ?? 0) }} - ₱{{ number_format($user->preferences->budget_max ?? 0) }}/mo</span>
                </div>
                @elseif($user->budget_min && $user->budget_max)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Budget: ₱{{ number_format($user->budget_min ?? 0) }} - ₱{{ number_format($user->budget_max ?? 0) }}/mo</span>
                </div>
                @endif
                
                @if($user->preferences && $user->preferences->move_in_date)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Move-in: {{ \Carbon\Carbon::parse($user->preferences->move_in_date)->format('M Y') }}</span>
                </div>
                @elseif($user->profile && $user->profile->move_in_date)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Move-in: {{ \Carbon\Carbon::parse($user->profile->move_in_date)->format('M Y') }}</span>
                </div>
                @endif
                
                <!-- Roommate Preferences -->
                @if($user->preference && $user->preference->number_of_roommates)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Roommates Wanted: {{ $user->preference->number_of_roommates }}</span>
                </div>
                @endif
                
                @if($user->preference && $user->preference->preferred_gender)
                <div class="flex items-center text-sm text-gray-400">
                    <svg class="h-4 w-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Preferred Gender: {{ ucfirst($user->preference->preferred_gender) }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-700 flex justify-between items-center">
            <a href="{{ route('profile.show', $user) }}" class="inline-flex items-center text-sm font-medium text-blue-400 hover:text-blue-300">
                View Profile
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <div class="flex space-x-2">
                @if(isset($match))
                    {{-- Match status buttons --}}
                    @if($match->status === 'accepted')
                        <a href="{{ route('messages.show', $user) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Message
                        </a>
                    @elseif($match->status === 'pending' && $match->user_id === auth()->id())
                        <span class="px-4 py-2 bg-yellow-900 bg-opacity-50 text-yellow-300 text-sm font-medium rounded-md border border-yellow-700">
                            Pending Response
                        </span>
                    @elseif($match->status === 'pending')
                        <form action="{{ route('matches.accept', $match) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" data-action="accept-match" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Accept
                            </button>
                        </form>
                        <form action="{{ route('matches.reject', $match) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @else
                        {{-- Default Add/Pass for unknown status --}}
                        <form action="{{ route('matches.like', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" data-action="like" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Add
                            </button>
                        </form>
                        <form action="{{ route('matches.dislike', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" data-action="dislike" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Pass
                            </button>
                        </form>
                    @endif
                @else
                    {{-- Potential match buttons (no match record yet) --}}
                    @if(isset($user->already_liked) && $user->already_liked)
                        {{-- User has been liked but not yet matched --}}
                        @if($user->match_status === 'pending')
                            <span class="px-4 py-2 bg-yellow-900 bg-opacity-50 text-yellow-300 text-sm font-medium rounded-md border border-yellow-700">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending
                            </span>
                        @else
                            <button disabled class="px-4 py-2 bg-gray-600 text-gray-400 text-sm font-medium rounded-md cursor-not-allowed border border-gray-700">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Added
                            </button>
                        @endif
                        <form action="{{ route('matches.dislike', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" data-action="dislike" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Pass
                            </button>
                        </form>
                    @else
                        {{-- Fresh user - show Add/Pass buttons --}}
                        <form action="{{ route('matches.like', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" data-action="like" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Add
                            </button>
                        </form>
                        <form action="{{ route('matches.dislike', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" data-action="dislike" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Pass
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Fix broken avatars
    document.querySelectorAll('.profile-avatar-img').forEach(function(img) {
        if (img.complete && img.naturalWidth === 0) {
            img.style.display = 'none';
        }
    });
})();
</script>
@endpush
