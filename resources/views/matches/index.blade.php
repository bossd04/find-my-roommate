@extends('layouts.app-with-sidebar')

@section('content')
    <!-- Hero Section with Background -->
    <div class="mb-8 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-20 right-20 w-32 h-32 bg-blue-600 rounded-full blur-2xl animate-pulse delay-75"></div>
            <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-blue-700 rounded-full blur-3xl animate-pulse delay-100"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 bg-blue-500 rounded-full blur-xl animate-pulse delay-150"></div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Your Matches</h1>
            <p class="text-blue-100 text-base sm:text-lg">Connect with potential roommates based on your preferences.</p>
        </div>
    </div>
    
    <!-- Filter and Sort Bar -->
    <div class="bg-gray-800 bg-opacity-95 backdrop-blur-md rounded-xl shadow-lg p-4 mb-8 border border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 thin-scrollbar">
                <a href="{{ route('matches.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600' }}">
                    All Matches
                </a>
                <a href="{{ route('matches.index', ['filter' => 'pending']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ $filter === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600' }}">
                    Pending
                </a>
                <a href="{{ route('matches.index', ['filter' => 'accepted']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ $filter === 'accepted' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600' }}">
                    Accepted
                </a>
                <a href="{{ route('matches.index', ['filter' => 'new']) }}" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ $filter === 'new' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600' }}">
                    New
                </a>
            </div>
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <span class="text-sm text-gray-400 whitespace-nowrap">Sort by:</span>
                <select class="bg-white border-gray-300 text-gray-900 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm">
                            <option>Compatibility</option>
                            <option>Recently Active</option>
                            <option>Distance</option>
                            <option>Price Range</option>
                        </select>
                    </div>
                </div>
                
                <!-- Match Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="matches-container">
                    @php
                        // Track displayed users to avoid duplicates
                        $displayedUserIds = [];
                    @endphp
                    
                    <!-- Show all matches (Pending and Accepted) -->
                    @if(isset($matches) && $matches->count() > 0)
                        @foreach($matches as $match)
                            @php
                                if(isset($match->display_user) && !in_array($match->display_user->id, $displayedUserIds)) {
                                    $displayedUserIds[] = $match->display_user->id;
                            @endphp
                                @include('matches.partials.profile-card', ['user' => $match->display_user, 'match' => $match])
                            @php
                                }
                            @endphp
                        @endforeach
                    @endif
                    
                    <!-- Show empty state if no matches at all -->
                    @if(empty($displayedUserIds))
                        <div class="col-span-3 text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-300">No matches found</h3>
                            <p class="mt-1 text-sm text-gray-500">
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
                                        this.classList.remove('bg-green-600', 'hover:bg-green-700');
                                        this.classList.add('bg-yellow-600', 'cursor-not-allowed');
                                        
                                        // Update compatibility score if provided
                                        if (data.compatibility_score) {
                                            const card = this.closest('.bg-gray-800');
                                            const compatibilityBadge = card.querySelector('.absolute.top-4.left-4 > div');
                                            if (compatibilityBadge) {
                                                const oldScore = compatibilityBadge.textContent.match(/\d+/)[0];
                                                const newScore = data.compatibility_score;
                                                compatibilityBadge.innerHTML = `
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                    </svg>
                                                    ${newScore}%
                                                `;
                                                
                                                // Update badge color based on new score
                                                compatibilityBadge.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500');
                                                const newColor = newScore >= 80 ? 'bg-green-500' : 
                                                               (newScore >= 60 ? 'bg-blue-500' : 
                                                               (newScore >= 40 ? 'bg-yellow-500' : 'bg-red-500'));
                                                compatibilityBadge.classList.add(newColor);
                                                
                                                // Show score increase notification
                                                if (newScore > oldScore) {
                                                    showNotification(`Compatibility increased from ${oldScore}% to ${newScore}%!`, 'success');
                                                }
                                            }
                                        }
                                        
                                        // Disable the pass button too
                                        const passButton = this.closest('div').querySelector('[data-action="dislike"]');
                                        if (passButton) {
                                            passButton.disabled = true;
                                            passButton.style.display = 'none';
                                        }
                                        
                                        // Keep user visible - don't remove from DOM
                                        // Just update the UI to show the new state
                                        
                                    } else {
                                        this.innerHTML = `
                                            <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Passed
                                        `;
                                        this.classList.remove('bg-red-600', 'hover:bg-red-700');
                                        this.classList.add('bg-gray-400', 'cursor-not-allowed');
                                        
                                        // Disable the add button too
                                        const addButton = this.closest('div').querySelector('[data-action="like"]');
                                        if (addButton) {
                                            addButton.disabled = true;
                                            addButton.style.display = 'none';
                                        }
                                        
                                        // Remove the user card after pass
                                        setTimeout(() => {
                                            const card = this.closest('.bg-gray-800');
                                            if (card) {
                                                card.style.opacity = '0';
                                                card.style.transform = 'scale(0.8)';
                                                setTimeout(() => card.remove(), 300);
                                            }
                                        }, 1000);
                                    }
                                    
                                    // Show notification
                                    showNotification(action === 'like' ? 'User added successfully!' : 'User passed', 'success');
                                    
                                    // Check for mutual match
                                    if (data.is_match) {
                                        showMatchNotification();
                                        // If it's a match, redirect after a delay
                                        setTimeout(() => {
                                            window.location.href = `/messages/${data.matched_user_id || this.closest('form').querySelector('input[name="user_id"]').value}`;
                                        }, 2000);
                                    }
                                });
                            } else {
                                // Fallback for non-JSON responses
                                if (action === 'like') {
                                    this.innerHTML = 'Added!';
                                    this.classList.add('bg-gray-400', 'cursor-not-allowed');
                                } else {
                                    this.innerHTML = 'Passed';
                                    this.classList.add('bg-gray-400', 'cursor-not-allowed');
                                }
                                showNotification(action === 'like' ? 'User added successfully!' : 'User passed', 'success');
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
                // Remove existing notifications
                const existingNotification = document.querySelector('.notification-toast');
                if (existingNotification) {
                    existingNotification.remove();
                }
                
                // Create new notification
                const notification = document.createElement('div');
                notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-xl flex items-center space-x-3 z-50 animate-fade-in-up ${
                    type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                } text-white`;
                
                notification.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${type === 'success' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4"></path>' :
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M16 12h4M12 20h.01"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-300');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
            
            function showMatchNotification() {
                alert('It\'s a match! You can now message each other.');
            }
        });
    </script>
    @endpush
@endsection
