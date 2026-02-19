<div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
    <!-- Profile Header -->
    <div class="relative">
        <img class="h-48 w-full object-cover" src="https://source.unsplash.com/random/400x300?person,{{ $loop->index ?? 0 }}" alt="Profile">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-4 left-4">
            <div class="flex items-center">
                <div class="h-14 w-14 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center overflow-hidden">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}?{{ time() }}" 
                             alt="{{ $user->fullName() }}" 
                             class="h-full w-full object-cover">
                    @else
                        <span class="text-xl font-bold text-gray-600">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-bold text-lg">{{ $user->fullName() ?? $user->name }}</h3>
                    @if($user->profile && $user->profile->age)
                    <p class="text-white text-sm">{{ $user->profile->age }} years old</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="absolute top-4 right-4">
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                {{ rand(70, 99) }}% Match
            </span>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="p-6">
        <div class="flex justify-between items-start">
            <div>
                <h4 class="font-semibold text-gray-900">{{ $user->first_name }}, {{ $user->age ?? rand(18, 35) }}</h4>
                <p class="text-sm text-gray-500">
                    <svg class="inline-block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $user->location ?? 'Location not specified' }}
                </p>
            </div>
        </div>

        @if($user->preferences)
            <!-- Preferences Section -->
            <div class="mt-4">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Preferences</h5>
                <div class="space-y-2">
                    @if($user->preferences->budget_min && $user->preferences->budget_max)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Budget: ${{ number_format($user->preferences->budget_min) }} - ${{ number_format($user->preferences->budget_max) }}/mo</span>
                    </div>
                    @endif
                    
                    @if($user->preferences->move_in_date)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Move-in: {{ \Carbon\Carbon::parse($user->preferences->move_in_date)->format('M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- About Section -->
        <div class="mt-4">
            <h5 class="text-sm font-medium text-gray-700 mb-2">About</h5>
            <p class="text-sm text-gray-600 line-clamp-3">
                @if($user->profile && $user->profile->bio)
                    {{ $user->profile->bio }}
                @else
                    {{ [
                        'Clean and organized roommate looking for a quiet place to study and relax.',
                        'Outgoing and social person who loves meeting new people.',
                        'Grad student who spends most days on campus.',
                        'Young professional working in tech.',
                        'Exchange student looking to explore the city.',
                        'Artist and part-time barista.'
                    ][$loop->index % 6] ?? 'No bio available' }}
                @endif
            </p>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('profile.show', $user) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                View Profile
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <div class="flex space-x-2">
                <form action="{{ route('matches.dislike', $user) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors duration-200" title="Dislike">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
                <form action="{{ route('matches.like', $user) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 bg-green-100 text-green-600 rounded-full hover:bg-green-200 transition-colors duration-200" title="Like">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
