<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Find Your Perfect Roommate') }}
            </h2>
            <div class="relative">
                <input type="text" placeholder="Search matches..." class="pl-10 pr-4 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="bg-black bg-opacity-50 min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Filter and Sort Bar -->
                <div class="bg-white rounded-xl shadow-md p-4 mb-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                        <a href="{{ route('matches.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            All Matches
                        </a>
                        <a href="{{ route('matches.index', ['filter' => 'pending']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $filter === 'pending' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            Pending
                        </a>
                        <a href="{{ route('matches.index', ['filter' => 'accepted']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $filter === 'accepted' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            Accepted
                        </a>
                        <a href="{{ route('matches.index', ['filter' => 'new']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ $filter === 'new' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            New
                        </a>
                    </div>
                    <div class="flex items-center space-x-4 w-full md:w-auto">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Sort by:</span>
                        <select class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            <option>Compatibility</option>
                            <option>Recently Active</option>
                            <option>Distance</option>
                            <option>Price Range</option>
                        </select>
                    </div>
                </div>
                
                <!-- Match Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="matches-container">
                    @if(isset($matches) && $matches->count() > 0)
                        @foreach($matches as $match)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                            <!-- Profile Header -->
                            <div class="relative">
                                <img class="h-48 w-full object-cover" src="https://source.unsplash.com/random/400x300?person,{{ $loop->index }}" alt="Profile">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <div class="flex items-center">
                                        <div class="h-14 w-14 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center overflow-hidden">
                                            <span class="text-xl font-bold text-gray-600">{{ strtoupper(substr($match->display_user->first_name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-white font-bold text-lg">{{ $match->display_user->fullName() }}</h3>
                                            @if($match->display_user->profile && $match->display_user->profile->age)
                                            <p class="text-white text-sm">{{ $match->display_user->profile->age }} years old</p>
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
                                        <h4 class="font-semibold text-gray-900">{{ $match->display_user->first_name }}, {{ $match->display_user->profile->age ?? rand(18, 35) }}</h4>
                                        <p class="text-sm text-gray-500">
                                            <svg class="inline-block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $match->display_user->profile->city ?? 'Location not specified' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Preferences Section -->
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Preferences</h5>
                                    <div class="space-y-2">
                                        @if($match->display_user->preferences)
                                            @if($match->display_user->preferences->budget_min && $match->display_user->preferences->budget_max)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Budget: ${{ number_format($match->display_user->preferences->budget_min) }} - ${{ number_format($match->display_user->preferences->budget_max) }}/mo</span>
                                            </div>
                                            @endif
                                            
                                            @if($match->display_user->preferences->move_in_date)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>Move-in: {{ \Carbon\Carbon::parse($match->display_user->preferences->move_in_date)->format('M Y') }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($match->display_user->preferences->location_preferences)
                                            <div class="flex items-start text-sm text-gray-600">
                                                <svg class="h-4 w-4 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>Looking in: {{ $match->display_user->preferences->location_preferences }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($match->display_user->preferences->lifestyle_preferences)
                                            <div class="flex items-start text-sm text-gray-600">
                                                <svg class="h-4 w-4 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                                <span>Lifestyle: {{ $match->display_user->preferences->lifestyle_preferences }}</span>
                                            </div>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-500 italic">No preferences specified yet</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- About Section -->
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">About</h5>
                                    <p class="text-sm text-gray-600 line-clamp-3">
                                        @if($match->display_user->profile && $match->display_user->profile->bio)
                                            {{ $match->display_user->profile->bio }}
                                        @else
                                            {{ [
                                                'Clean and organized roommate looking for a quiet place to study and relax.',
                                                'Outgoing and social person who loves meeting new people.',
                                                'Grad student who spends most days on campus.',
                                                'Young professional working in tech.',
                                                'Exchange student looking to explore the city.',
                                                'Artist and part-time barista.'
                                            ][$loop->index % 6] }}
                                        @endif
                                    </p>
                                </div>


                                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <a href="{{ route('profile.show', $match->display_user) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                        View Profile
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                    <div class="flex space-x-2">
                                        @if($match->status === 'accepted')
                                            <a href="{{ route('messages.show', $match->display_user) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                Message
                                            </a>
                                        @elseif($match->status === 'pending' && $match->user_id === auth()->id())
                                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-md">
                                                Pending
                                            </span>
                                        @elseif($match->status === 'pending')
                                            <form action="{{ route('matches.accept', $match) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
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
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @elseif(isset($potentialMatches) && $potentialMatches->count() > 0)
                        @foreach($potentialMatches as $user)
                            @include('matches.partials.profile-card', ['user' => $user])
                        @endforeach
                    @else
                        <div class="col-span-3 text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No matches found</h3>
                            <p class="mt-1 text-sm text-bold-black-500">
                                @if($filter === 'all')
                                    There are no potential matches at the moment. Check back later!
                                @else
                                    No {{ $filter }} matches found. Try adjusting your filters.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if(isset($potentialMatches) && method_exists($potentialMatches, 'hasPages') && $potentialMatches->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $potentialMatches->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle accept match
            document.addEventListener('click', async function(e) {
                if (e.target.closest('[data-action="accept-match"]')) {
                    e.preventDefault();
                    const form = e.target.closest('form');
                    const url = form.action;
                    const matchId = form.dataset.matchId;
                    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                _token: '{{ csrf_token() }}',
                                _method: 'PUT',
                                status: 'accepted'
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                // Reload the page to show updated status
                                window.location.reload();
                            }
                        } else {
                            alert(data.message || 'An error occurred. Please try again.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                }
            });
            
            // Original code continues
            // Filter buttons
            const filterButtons = document.querySelectorAll('[data-filter]');
            const currentFilter = '{{ $filter }}';
            
            // Highlight current filter
            filterButtons.forEach(button => {
                if (button.getAttribute('data-filter') === currentFilter) {
                    button.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    button.classList.add('bg-indigo-600', 'text-white');
                }
                
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.getAttribute('data-filter');
                    window.location.href = '{{ route("matches.index") }}?filter=' + filter;
                });
            });
            
            // Handle like/dislike buttons
            const likeButtons = document.querySelectorAll('[data-action="like"], [data-action="dislike"]');
            likeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const url = form.action;
                    const data = new FormData(form);
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _token: data.get('_token'),
                            _method: 'POST'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const card = form.closest('.bg-white');
                            card.classList.add('transform', 'scale-0', 'opacity-0');
                            setTimeout(() => card.remove(), 300);
                            
                            // Show match notification if it's a match
                            if (data.is_match) {
                                showMatchNotification();
                            }
                        }
                    });
                });
            });
            
            function showMatchNotification() {
                // Implement match notification UI here
                alert('It\'s a match!');
            }
        });
    </script>
    @endpush
</x-app-layout>
