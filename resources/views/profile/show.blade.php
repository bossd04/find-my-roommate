<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $user->fullName() }}
            </h2>
            @if(isset($compatibilityScore))
            <div class="flex items-center">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-700 mr-2">Compatibility:</span>
                    <div class="relative">
                        <div class="h-6 w-32 bg-gray-200 rounded-full overflow-hidden">
                            @php
                                $percentage = min(100, max(0, $compatibilityScore));
                                $color = $percentage >= 80 ? 'bg-green-500' : ($percentage >= 60 ? 'bg-blue-500' : 'bg-yellow-500');
                            @endphp
                            <div class="h-full {{ $color }} rounded-full transition-all duration-500 ease-in-out" 
                                 style="width: {{ $percentage }}%">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}?{{ time() }}" 
                                     alt="{{ $user->name }}" 
                                     class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-md">
                            @elseif($user->avatar_url)
                                <img src="{{ $user->avatar_url }}?{{ time() }}" 
                                     alt="{{ $user->name }}" 
                                     class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-md">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-600">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->fullName() }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            @if($profile)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @if($profile->bio)
                                        <p class="text-gray-700">{{ $profile->bio }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($profile)
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @if($profile->age)
                                    <div>
                                        <p class="text-sm text-gray-500">Age</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $profile->age }}</p>
                                    </div>
                                @endif
                                @if($profile->gender)
                                    <div>
                                        <p class="text-sm text-gray-500">Gender</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $profile->gender }}</p>
                                    </div>
                                @endif
                                @if($profile->occupation)
                                    <div>
                                        <p class="text-sm text-gray-500">Occupation</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $profile->occupation }}</p>
                                    </div>
                                @endif
                                @if($profile->budget)
                                    <div>
                                        <p class="text-sm text-gray-500">Budget</p>
                                        <p class="mt-1 text-sm text-gray-900">${{ number_format($profile->budget) }}/month</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->id() !== $user->id)
                        <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-4">
                            @if($isMatch ?? false)
                                <a href="{{ route('messages.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    Message
                                </a>
                            @else
                                <form action="{{ route('matches.like', $user) }}" method="POST" class="inline" onsubmit="handleLikeSubmit(event)">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        Like
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('matches.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Back to Matches
                            </a>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function handleLikeSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    
    // Show loading state
    if (button) {
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4.374a6.375 6.375 0 014.625 1.25H6.375A6.375 6.375 0 00-6.375-6.375H12a1 1 0 011 1v3a1 1 0 011-1h.375A6.375 6.375 0 011.625 1.25H12a1 1 0 011-1V8a8 8 0 018-8z"></path>
            </svg>
            Liking...
        `;
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success feedback
            if (button) {
                button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Liked!
                `;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-gray-400', 'cursor-not-allowed');
                button.disabled = true;
            }
            
            // Show success notification
            showNotification('Profile liked successfully!', 'success');
        } else {
            // Show error feedback
            if (button) {
                button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Like
                `;
            }
            
            showNotification(data.message || 'Error liking profile', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (button) {
            button.innerHTML = `
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Like
                `;
        }
        
        showNotification('Network error. Please try again.', 'error');
    });
}

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
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.cursor-not-allowed {
    cursor: not-allowed;
}
</style>
