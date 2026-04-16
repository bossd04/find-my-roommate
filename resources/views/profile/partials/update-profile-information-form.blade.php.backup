<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update.information') }}" class="mt-6 space-y-6" x-data="{ showNotification: false, notificationMessage: '', notificationType: '' }">
        @csrf
        @method('patch')

        <!-- Debug Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-blue-800">🔍 Debug Information</h4>
                <button type="button" @click="showNotification = true; notificationMessage = 'Debug info refreshed'; notificationType = 'info'" class="text-xs text-blue-600 hover:text-blue-800">Refresh</button>
            </div>
            <div class="mt-2 text-xs text-blue-700 space-y-1">
                <div><strong>User ID:</strong> {{ $user->id }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Profile Complete:</strong> {{ $user->isProfileComplete() ? 'Yes ✅' : 'No ❌' }}</div>
                <div><strong>Last Updated:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
                <div><strong>Session ID:</strong> {{ session()->getId() }}</div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone ?? '')" placeholder="Enter your phone number" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="location" :value="__('Location')" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $user->location ?? '')" placeholder="Enter your location (City, Province)" required />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
            
            <!-- Location Display -->
            <div id="location_display" class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md text-sm text-green-700" style="display: none;">
                <span id="location_text"></span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" id="save-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <span id="save-icon" class="mr-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span id="save-text">{{ __('Save') }}</span>
            </button>

            <!-- Success Indicator -->
            <div id="success-indicator" class="hidden flex items-center text-green-600">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-sm font-medium">Saved successfully!</span>
            </div>

            <!-- Error Indicator -->
            <div id="error-indicator" class="hidden flex items-center text-red-600">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="text-sm font-medium">Error!</span>
            </div>

            <!-- Notification Display -->
            <div x-show="showNotification" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform translate-y-2"
                 x-init="setTimeout(() => showNotification = false, 5000)"
                 :class="{
                     'bg-green-100 border-green-400 text-green-700': notificationType === 'success',
                     'bg-blue-100 border-blue-400 text-blue-700': notificationType === 'info',
                     'bg-red-100 border-red-400 text-red-700': notificationType === 'error'
                 }"
                 class="border px-4 py-3 rounded relative" 
                 role="alert">
                <span class="block sm:inline" x-text="notificationMessage"></span>
                <button type="button" @click="showNotification = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </button>
            </div>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle location input with real-time display
    const locationInput = document.getElementById('location');
    const locationDisplay = document.getElementById('location_display');
    const locationText = document.getElementById('location_text');
    
    if (locationInput && locationDisplay && locationText) {
        // Initialize display on page load
        if (locationInput.value) {
            locationText.textContent = '📍 Selected: ' + locationInput.value;
            locationDisplay.style.display = 'block';
        }
        
        // Add change event listener
        locationInput.addEventListener('input', function() {
            if (this.value) {
                locationText.textContent = '📍 Selected: ' + this.value;
                locationDisplay.style.display = 'block';
            } else {
                locationDisplay.style.display = 'none';
            }
        });
    }
    
    // Handle form submission with visual feedback
    const form = document.querySelector('form[action*="profile/update/information"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate location field
            const locationInput = document.getElementById('location');
            if (locationInput && !locationInput.value.trim()) {
                // Show error for empty location
                if (window.Alpine && window.Alpine.store) {
                    window.Alpine.store('notification', {
                        message: 'Location is required. Please enter your location.',
                        type: 'error',
                        show: true
                    });
                }
                return;
            }
            
            const saveButton = document.getElementById('save-button');
            const saveIcon = document.getElementById('save-icon');
            const saveText = document.getElementById('save-text');
            const successIndicator = document.getElementById('success-indicator');
            const errorIndicator = document.getElementById('error-indicator');
            
            // Store original button state
            const originalIcon = saveIcon.innerHTML;
            const originalText = saveText.textContent;
            
            // Show loading state
            saveButton.disabled = true;
            saveIcon.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            saveText.textContent = 'Saving...';
            
            // Hide any existing indicators
            successIndicator.classList.add('hidden');
            errorIndicator.classList.add('hidden');
            
            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success state
                    saveIcon.innerHTML = `
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `;
                    saveText.textContent = 'Saved!';
                    saveButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    saveButton.classList.add('bg-green-600', 'hover:bg-green-700');
                    
                    // Show success indicator
                    successIndicator.classList.remove('hidden');
                    
                    // Show notification using Alpine.js if available
                    if (window.Alpine && window.Alpine.store) {
                        window.Alpine.store('notification', {
                            message: 'Profile information updated successfully!',
                            type: 'success',
                            show: true
                        });
                    }
                    
                    // Reset button after delay
                    setTimeout(() => {
                        saveIcon.innerHTML = originalIcon;
                        saveText.textContent = originalText;
                        saveButton.disabled = false;
                        saveButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                        saveButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        successIndicator.classList.add('hidden');
                    }, 3000);
                    
                    // Log debug info
                    console.log('Profile Information Update Debug Info:', data.debug_info);
                    
                } else {
                    // Show error state
                    saveIcon.innerHTML = `
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    `;
                    saveText.textContent = 'Error';
                    saveButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    saveButton.classList.add('bg-red-600', 'hover:bg-red-700');
                    
                    // Show error indicator
                    errorIndicator.classList.remove('hidden');
                    
                    // Show error notification
                    if (window.Alpine && window.Alpine.store) {
                        window.Alpine.store('notification', {
                            message: data.message || 'Error updating profile information',
                            type: 'error',
                            show: true
                        });
                    }
                    
                    // Reset button after delay
                    setTimeout(() => {
                        saveIcon.innerHTML = originalIcon;
                        saveText.textContent = originalText;
                        saveButton.disabled = false;
                        saveButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                        saveButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        errorIndicator.classList.add('hidden');
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Profile information update error:', error);
                
                // Show error state
                saveIcon.innerHTML = `
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                `;
                saveText.textContent = 'Error';
                saveButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                saveButton.classList.add('bg-red-600', 'hover:bg-red-700');
                
                // Show error indicator
                errorIndicator.classList.remove('hidden');
                
                // Show error notification
                if (window.Alpine && window.Alpine.store) {
                    window.Alpine.store('notification', {
                        message: 'Network error. Please try again.',
                        type: 'error',
                        show: true
                    });
                }
                
                // Reset button after delay
                setTimeout(() => {
                    saveIcon.innerHTML = originalIcon;
                    saveText.textContent = originalText;
                    saveButton.disabled = false;
                    saveButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                    saveButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    errorIndicator.classList.add('hidden');
                }, 3000);
            });
        });
    }
});
</script>
