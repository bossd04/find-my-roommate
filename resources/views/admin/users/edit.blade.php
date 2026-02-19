@extends('admin.layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-600">Update user details and permissions.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-eye mr-2"></i> View Profile
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form id="user-edit-form" method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm(event)">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Profile Photo -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Profile Photo
                        </label>
                        <div class="mt-2 flex items-start">
                            <div class="relative">
                                <div class="relative h-32 w-32 rounded-full overflow-hidden bg-gray-100 group" id="profile-photo-container">
                                    @if($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover" id="profile-photo-preview">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gray-200">
                                            <svg class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full">
                                        <span class="text-white text-sm font-medium">Change Photo</span>
                                    </div>
                                </div>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/jpeg,image/png,image/gif" onchange="previewProfilePhoto(this)">
                                <div class="absolute -bottom-2 -right-2">
                                    <label for="profile_photo" class="cursor-pointer bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </label>
                                </div>
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="space-y-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Upload a new photo</h3>
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, or GIF. Max size: 5GB</p>
                                        <div class="mt-2 flex space-x-2">
                                            <div class="space-y-2">
                                                <label for="profile_photo" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5.5 13a3.5 3.5 0 01-.369-6.998 5.002 5.002 0 019.738-1.046 3.5 3.5 0 01-3.369 5.044H5.5z" />
                                                    </svg>
                                                    Change Photo
                                                </label>
                                                @if($user->profile_photo_path)
                                                    <button type="button" onclick="document.getElementById('remove_photo').click()" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Remove
                                                    </button>
                                                @endif
                                            </div>
                                        
                                        @if($user->profile_photo_path)
                                        <div class="border-t border-gray-200 pt-4">
                                            <h3 class="text-sm font-medium text-gray-700">Current Photo</h3>
                                            <p class="mt-1 text-xs text-gray-500">This is how your profile photo appears to others.</p>
                                        </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="first_name" id="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   required>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="last_name" id="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   required>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            New Password (leave blank to keep current)
                        </label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePasswordVisibility('password')" class="text-gray-400 hover:text-gray-500">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm New Password
                        </label>
                        <div class="mt-1 relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" 
                                   autocomplete="new-password"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="text-gray-400 hover:text-gray-500">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="role" name="role" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $user->is_admin ? 'admin' : 'user') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="sm:col-span-3">
                        <label for="gender" class="block text-sm font-medium text-gray-700">
                            Gender
                        </label>
                        <div class="mt-1">
                            <select id="gender" name="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Gender</option>
                                @foreach($genders as $value => $label)
                                    <option value="{{ $value }}" {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="sm:col-span-3">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                            Date of Birth
                        </label>
                        <div class="mt-1 relative">
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   max="{{ now()->subYears(13)->format('Y-m-d') }}"
                                   title="Please select a date (must be at least 13 years ago)">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Click to open calendar selector</p>
                    </div>

                    <!-- Phone -->
                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number
                        </label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="sm:col-span-6">
                        <label for="bio" class="block text-sm font-medium text-gray-700">
                            Bio
                        </label>
                        <div class="mt-1">
                            <textarea id="bio" name="bio" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('bio', $user->bio) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">A short bio about the user.</p>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-6 pt-2 border-t border-gray-200">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active Account
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Deactivating will prevent this user from logging in.</p>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <a href="{{ route('admin.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="button" 
                                    onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form').submit(); }" 
                                    class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash mr-2"></i> Delete User
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i> Update User
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation before submission
    function validateForm(event) {
        const fileInput = document.getElementById('profile_photo');
        if (fileInput && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 5 * 1024 * 1024 * 1024; // 5GB in bytes
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (JPG, PNG, or GIF)');
                return false;
            }
            
            if (file.size > maxSize) {
                alert('File size exceeds 5GB limit');
                return false;
            }
        }
        return true;
    }

    // Toggle password visibility
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Profile photo preview and validation - defined globally
    window.previewProfilePhoto = function(input) {
        const file = input.files[0];
        const maxSize = 5 * 1024 * 1024 * 1024; // 5GB in bytes
        const container = document.getElementById('profile-photo-container');
        
        // Check if file is selected
        if (!file) {
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, or GIF)');
            input.value = ''; // Clear the file input
            return;
        }
        
        // Check file size
        if (file.size > maxSize) {
            alert('File size exceeds 5GB limit');
            input.value = ''; // Clear the file input
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            // Clear the container but keep the hover effect
            container.innerHTML = `
                <div class="relative h-32 w-32 rounded-full overflow-hidden bg-gray-100 group">
                    <img src="${e.target.result}" alt="Preview" class="h-full w-full object-cover" id="profile-photo-preview">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full">
                        <span class="text-white text-sm font-medium">Change Photo</span>
                    </div>
                </div>
            `;
            
            // Re-attach the file input event
            const newFileInput = document.createElement('input');
            newFileInput.type = 'file';
            newFileInput.name = 'profile_photo';
            newFileInput.id = 'profile_photo';
            newFileInput.className = 'hidden';
            newFileInput.accept = 'image/jpeg,image/png,image/gif';
            newFileInput.setAttribute('form', 'user-edit-form');
            newFileInput.onchange = function() { previewProfilePhoto(this); };
            
            // Add the camera icon back
            const cameraIcon = document.createElement('div');
            cameraIcon.className = 'absolute -bottom-2 -right-2';
            cameraIcon.innerHTML = `
                <label for="profile_photo" class="cursor-pointer bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </label>
            `;
            
            container.appendChild(newFileInput);
            container.appendChild(cameraIcon);
            
            // If remove photo checkbox is checked, uncheck it
            const removeCheckbox = document.getElementById('remove_photo');
            if (removeCheckbox && removeCheckbox.checked) {
                removeCheckbox.checked = false;
            }
        };
        
        reader.readAsDataURL(file);
    }
    
    // Initialize everything when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize file input event listener
        const fileInput = document.getElementById('profile_photo');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (window.previewProfilePhoto) {
                    window.previewProfilePhoto(this);
                } else {
                    console.error('previewProfilePhoto function is not defined');
                }
            });
        }
        
        // Add date input validation
        const dateInput = document.getElementById('date_of_birth');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const minDate = new Date();
                minDate.setFullYear(minDate.getFullYear() - 100); // 100 years ago
                const maxDate = new Date();
                maxDate.setFullYear(maxDate.getFullYear() - 13); // 13 years ago
                
                if (selectedDate > maxDate) {
                    this.value = maxDate.toISOString().split('T')[0];
                    alert('Date of birth must be at least 13 years ago.');
                } else if (selectedDate < minDate) {
                    this.value = minDate.toISOString().split('T')[0];
                    alert('Date of birth cannot be more than 100 years ago.');
                }
            });
        }
    });
</script>
@endpush
