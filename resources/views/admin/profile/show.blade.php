@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-1 text-sm text-gray-500">Update your account's profile information and password.</p>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="p-6 sm:p-8">
                <!-- Profile Photo Section -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900">Profile Photo</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your profile photo</p>
                    
                    <div class="mt-4 flex items-center">
                        <div class="relative group">
                            <div class="h-16 w-16 rounded-full overflow-hidden bg-white border-[0.5px] border-gray-200 flex items-center justify-center">
                                @if(auth()->user()->profile_photo_path)
                                    <img id="profile-photo-preview" 
                                         src="{{ auth()->user()->profile_photo_url }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="h-full w-full object-cover">
                                @else
                                    <span id="profile-initials" class="text-xl font-medium text-gray-500">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                @endif
                                
                                <!-- Upload Overlay -->
                                <div id="uploading-overlay" class="hidden absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center">
                                    <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Camera Icon - Smaller and more subtle -->
                            <div class="absolute -bottom-0.5 -right-0.5 bg-white p-0.5 rounded-full shadow-sm border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <label for="profile_photo" class="cursor-pointer text-gray-500 hover:text-indigo-500 block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                <label for="profile_photo" class="cursor-pointer text-gray-500 hover:text-indigo-500 block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" 
                                           id="profile_photo" 
                                           name="profile_photo" 
                                           class="sr-only" 
                                           accept="image/*"
                                           onchange="handleProfilePhotoUpload(this)">
                                </label>
                            </div>
                        </div>
                        
                        <div class="ml-6">
                            <p class="text-sm text-gray-500">JPG, PNG or GIF (max 2MB)</p>
                            <p id="upload-message" class="mt-1 text-sm"></p>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <div class="mt-1">
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', auth()->user()->name) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           autocomplete="name">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <div class="mt-1">
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', auth()->user()->email) }}" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           autocomplete="email">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="pt-6">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="border-t border-gray-200 pt-8 mt-8">
                    <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <div class="mt-1">
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           autocomplete="current-password">
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <div class="mt-1">
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           autocomplete="new-password">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <div class="mt-1">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function handleProfilePhotoUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showUploadMessage('Please upload a valid image file (JPG, PNG, GIF)', 'error');
                return;
            }
            
            // Validate file size
            if (file.size > maxSize) {
                showUploadMessage('File size must be less than 2MB', 'error');
                return;
            }
            
            // Show loading state
            document.getElementById('uploading-overlay').classList.remove('hidden');
            document.getElementById('upload-message').textContent = 'Uploading...';
            document.getElementById('upload-message').className = 'mt-2 text-xs text-blue-600 text-center';
            
            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profile-photo-preview');
                const initials = document.getElementById('profile-initials');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (initials) {
                    initials.outerHTML = `<img id="profile-photo-preview" 
                                            src="${e.target.result}" 
                                            alt="${document.querySelector('input[name="name"]').value}" 
                                            class="h-full w-full object-cover">`;
                }
            };
            reader.readAsDataURL(file);
            
            // Upload the file
            const formData = new FormData();
            formData.append('profile_photo', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route("admin.profile.photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showUploadMessage('Profile photo updated successfully!', 'success');
                    
                    // Update the user's avatar in the sidebar
                    const sidebarAvatar = document.querySelector('.sidebar-avatar');
                    if (sidebarAvatar) {
                        sidebarAvatar.src = data.photo_url + '?v=' + new Date().getTime();
                    }
                    
                    // Update the avatar in the top navigation
                    const navAvatar = document.querySelector('.nav-avatar');
                    if (navAvatar) {
                        navAvatar.src = data.photo_url + '?v=' + new Date().getTime();
                    }
                } else {
                    throw new Error(data.message || 'Failed to upload profile photo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = error.message || 'An error occurred while uploading the photo';
                showUploadMessage(errorMessage, 'error');
                
                // Revert to initials if there was an error and no previous image
                if (!'{{ auth()->user()->profile_photo_path }}') {
                    const preview = document.getElementById('profile-photo-preview');
                    if (preview) {
                        preview.outerHTML = `
                            <span id="profile-initials" class="text-4xl text-indigo-600 font-medium">
                                ${document.querySelector('input[name="name"]').value.charAt(0).toUpperCase()}
                            </span>`;
                    }
                }
            })
            .finally(() => {
                document.getElementById('uploading-overlay').classList.add('hidden');
            });
        }
    }
    
    function showUploadMessage(message, type = 'info') {
        const messageElement = document.getElementById('upload-message');
        if (messageElement) {
            messageElement.textContent = message;
            messageElement.className = `mt-2 text-xs text-${type === 'error' ? 'red' : type === 'success' ? 'green' : 'gray'}-600 text-center`;
            
            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    messageElement.textContent = 'JPG, PNG or GIF (max 2MB)';
                    messageElement.className = 'mt-2 text-xs text-gray-500 text-center';
                }, 5000);
            }
        }
    }
</script>
@endpush
@endsection
