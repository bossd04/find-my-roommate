@extends('layouts.app-with-sidebar')

@section('content')
    <!-- Hero Section with Background -->
    <!-- Hero Section with Background -->
    <div class="mb-8 rounded-2xl p-6 sm:p-10 text-white relative overflow-hidden shadow-2xl" 
         style="background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-20 right-20 w-32 h-32 bg-indigo-600 rounded-full blur-2xl animate-pulse delay-75"></div>
            <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-blue-700 rounded-full blur-3xl animate-pulse delay-100"></div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Find Your Perfect Roommate</h1>
            <p class="text-blue-100 text-lg">Browse and connect with compatible roommates based on shared preferences and lifestyle.</p>
        </div>
    </div>
            
    <!-- Enhanced Search Section (Updated for consistency) -->
    <div class="bg-white dark:bg-gray-800 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-8 border border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('roommates.index') }}" class="space-y-4">
            <!-- Search and Location Row -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="Search by name, email, university..." 
                           class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           value="{{ request('search') }}">
                </div>
                
                <div class="relative md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <select name="location" class="block w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                        <option value="">All Locations</option>
                        <option value="Alaminos City">Alaminos City</option>
                        <option value="Dagupan City">Dagupan City</option>
                        <option value="San Carlos City">San Carlos City</option>
                        <option value="Urdaneta City">Urdaneta City</option>
                        <!-- ... rest of the options remain the same ... -->
                        @php
                            $locations = ["Agno", "Aguilar", "Alcala", "Anda", "Asingan", "Balungao", "Bani", "Basista", "Bautista", "Bayambang", "Binalonan", "Binmaley", "Bolinao", "Buenavista", "Bugallon", "Burgos", "Calasiao", "Dasol", "Herrera", "Infanta", "Labrador", "Laoac", "Lingayen", "Mabini", "Malasiqui", "Mangaldan", "Mapandan", "Natividad", "Pozorrubio", "Quezon", "Rosales", "Rosario", "San Fabian", "San Jacinto", "San Manuel", "San Nicolas", "San Quintin", "Santa Barbara", "Santa Maria", "Santo Tomas", "Sison", "Tayug", "Umingan", "Urbiztondo", "Villasis"];
                        @endphp
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/20 active:scale-95 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
            </div>
            
            @if(request('location'))
                <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-800 rounded-xl p-3">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Showing results for <strong class="font-semibold">{{ request('location') }}</strong>
                    </p>
                </div>
            @endif
        </form>
    </div>
                    
            <!-- User Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @if(isset($users) && $users->count() > 0)
                    @foreach($users as $user)
                        @if($user->id !== auth()->id())  <!-- Don't show current user -->
                            @include('matches.partials.profile-card', ['user' => $user])
                        @endif
                    @endforeach
                @else
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
                @endif
            </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle like/dislike buttons
            const likeButtons = document.querySelectorAll('[data-action="like"], [data-action="dislike"]');
            likeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const url = form.action;
                    const data = new FormData(form);
                    const action = this.getAttribute('data-action');
                    const originalText = this.innerHTML;
                    
                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = `<span class="animate-pulse">${action === 'like' ? 'Adding...' : 'Passing...'}</span>`;
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json, text/html',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams(data)
                    })
                    .then(response => {
                        if (response.ok) {
                            // Parse JSON response
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json().then(data => {
                                    // Success - show feedback
                                    if (action === 'like') {
                                        this.innerHTML = `
                                            <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Pending
                                        `;
                                        this.classList.remove('bg-green-600', 'hover:bg-green-700', 'bg-blue-600', 'hover:bg-blue-700');
                                        this.classList.add('bg-yellow-600', 'cursor-not-allowed');
                                        
                                        // Disable the pass button too
                                        const passButton = this.closest('div').querySelector('[data-action="dislike"]');
                                        if (passButton) {
                                            passButton.disabled = true;
                                            passButton.style.display = 'none';
                                        }
                                        
                                    } else {
                                        this.innerHTML = `
                                            <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Passed
                                        `;
                                        this.classList.remove('bg-red-600', 'hover:bg-red-700', 'bg-gray-200');
                                        this.classList.add('bg-gray-400', 'cursor-not-allowed');
                                        
                                        // Disable the add button too
                                        const addButton = this.closest('div').querySelector('[data-action="like"]');
                                        if (addButton) {
                                            addButton.disabled = true;
                                            addButton.style.display = 'none';
                                        }
                                        
                                        // Remove the user card after pass
                                        setTimeout(() => {
                                            const card = this.closest('.bg-gray-800, .backdrop-blur-md');
                                            if (card) {
                                                card.style.opacity = '0';
                                                card.style.transform = 'scale(0.8)';
                                                setTimeout(() => card.remove(), 300);
                                            }
                                        }, 1000);
                                    }
                                    
                                    // Show notification
                                    showNotification(action === 'like' ? 'User added to pending matches!' : 'User passed', 'success');
                                    
                                    // Check for mutual match
                                    if (data.is_match) {
                                        showMatchNotification();
                                        // If it's a match, redirect after a delay
                                        setTimeout(() => {
                                            window.location.href = `/messages/${data.matched_user_id || this.closest('form').querySelector('input[name="user_id"]').value}`;
                                        }, 2000);
                                    }
                                });
                            }
                        } else {
                            throw new Error('Network response was not ok');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.disabled = false;
                        this.innerHTML = originalText;
                        showNotification('Something went wrong. Please try again.', 'error');
                    });
                });
            });
            
            function showNotification(message, type = 'info') {
                const existingNotification = document.querySelector('.notification-toast');
                if (existingNotification) existingNotification.remove();
                
                const notification = document.createElement('div');
                notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-xl shadow-2xl flex items-center space-x-3 z-50 transition-all duration-300 ${
                    type === 'success' ? 'bg-green-600' : 'bg-red-600'
                } text-white`;
                
                notification.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold whitespace-nowrap">${message}</span>
                `;
                
                document.body.appendChild(notification);
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'translate-y-[-20px]');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
            
            function showMatchNotification() {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black bg-opacity-80 backdrop-blur-sm animate-fade-in';
                modal.innerHTML = `
                    <div class="bg-gray-800 rounded-3xl p-8 max-w-sm w-full text-center border border-indigo-500 shadow-2xl shadow-indigo-500/20 transform animate-bounce-in">
                        <div class="w-20 h-20 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-pink-500/50">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-white mb-2">It's a Match!</h2>
                        <p class="text-indigo-200 mb-6">You and this roommate have liked each other!</p>
                        <div class="animate-pulse text-sm text-indigo-400">Redirecting to chat...</div>
                    </div>
                `;
                document.body.appendChild(modal);
            }
        });
    </script>
    @endpush
@endsection
