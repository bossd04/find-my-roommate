<x-app-layout>
    <x-slot name="header">
        <meta name="cache-control" content="no-cache, no-store, must-revalidate">
        <meta name="pragma" content="no-cache">
        <meta name="expires" content="0">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Details Form -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-6xl">
                    @include('profile.partials.update-profile-details-form')
                </div>
            </div>

            <!-- Email and Password Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">{{ __('Account Settings') }}</h3>
                    
                    <div class="space-y-6">
                        <div class="pt-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    
    <!-- Force Cache Busting Script -->
    <script>
        // Force page reload to bypass browser cache
        if (performance.navigation.type !== 1) {
            console.log('Forcing page reload to bypass cache...');
            if ('caches' in window) {
                caches.keys().then(function(names) {
                    names.forEach(function(name) {
                        caches.delete(name);
                    });
                });
            }
        }
    </script>
    
    @if (session('status') === 'profile-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 animate-fade-in-up';
                notification.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Profile updated successfully!</span>
                `;
                
                // Add to body
                document.body.appendChild(notification);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-300');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            });
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
        </style>
    @endif
</x-app-layout>
