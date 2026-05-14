@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="relative overflow-hidden bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Profile Settings</h1>
                    <p class="mt-2 text-sm font-bold text-gray-500 uppercase tracking-widest">Update your account's profile information and password.</p>
                </div>
                <div class="mt-6 md:mt-0 flex items-center bg-indigo-50 dark:bg-indigo-900/20 px-6 py-3 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest leading-none mr-3">Status:</span>
                    <span class="text-xs font-black text-emerald-500 uppercase tracking-widest flex items-center">
                        <i class="fas fa-circle text-[8px] mr-2 animate-pulse"></i> Online
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Profile Photo & Primary Info Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <!-- Photo Section -->
                    <div class="flex flex-col md:flex-row md:items-center mb-10 gap-10">
                        <div class="relative group mx-auto md:mx-0">
                            <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-50 dark:bg-gray-900 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center transition-all duration-500 group-hover:scale-105">
                                @if(auth()->user()->profile_photo_path)
                                    <img id="profile-photo-preview" 
                                         src="{{ auth()->user()->profile_photo_url }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="h-full w-full object-cover">
                                @else
                                    <div id="profile-initials" class="text-3xl font-black text-indigo-600 dark:text-indigo-400">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div id="uploading-overlay" class="hidden absolute inset-0 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center">
                                    <i class="fas fa-circle-notch fa-spin text-white text-2xl"></i>
                                </div>
                            </div>
                             
                            <label for="profile_photo" class="absolute bottom-0 right-0 p-3 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-500/30 cursor-pointer hover:bg-indigo-700 transition-all hover:-translate-y-1 active:scale-90 border-4 border-white dark:border-gray-800">
                                <i class="fas fa-camera text-sm"></i>
                                <input type="file" id="profile_photo" name="profile_photo" class="sr-only" accept="image/*" onchange="handleProfilePhotoUpload(this)">
                            </label>
                        </div>
                        
                        <div class="flex-1 text-center md:text-left">
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Your Profile Photo</h2>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 leading-relaxed">Customize your appearance across the platform. <br>Recommended: Square JPG or PNG, 800x800px.</p>
                            <p id="upload-message" class="text-xs font-black uppercase tracking-widest text-indigo-500"></p>
                        </div>
                    </div>
                    
                    <!-- Information Form -->
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-10">
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-8">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                           class="block w-full px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border border-transparent rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500">
                                    @error('name') <p class="mt-1 text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                           class="block w-full px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border border-transparent rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500">
                                    @error('email') <p class="mt-1 text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all active:scale-95">
                                    <i class="fas fa-save mr-3"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Security & Password</h2>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-10">Ensure your account is protected with a long, random password.</p>
                    
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <input type="password" name="current_password" id="current_password" 
                                       class="block w-full px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border border-transparent rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500" 
                                       placeholder="••••••••">
                                @error('current_password') <p class="mt-1 text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <input type="password" name="password" id="password" 
                                           class="block w-full px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border border-transparent rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500" 
                                           placeholder="Enter new password">
                                    @error('password') <p class="mt-1 text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="block w-full px-4 py-3 bg-gray-50/50 dark:bg-gray-900/50 border border-transparent rounded-2xl text-sm font-black text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500" 
                                           placeholder="Confirm new password">
                                    @error('password_confirmation') <p class="mt-1 text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-10 py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-gray-800 dark:hover:bg-gray-100 shadow-xl transition-all active:scale-95">
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
