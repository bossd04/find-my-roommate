@extends('admin.layouts.app')

@section('title', 'Create New User')

@push('styles')
<style>
    /* Responsive Contrast System */
    .high-contrast-title { color: #020617 !important; }
    .high-contrast-subtext { color: #374151 !important; }
    
    .dark .high-contrast-title { 
        color: #ffffff !important; 
        text-shadow: 0 0 30px rgba(255,255,255,0.1);
    }
    .dark .high-contrast-subtext { 
        color: #e2e8f0 !important; 
    }
    
    .dark .text-gray-500 { color: #9ca3af !important; }
    .dark .text-gray-400 { color: #818cf8 !important; }
    .dark .text-gray-600 { color: #cbd5e1 !important; }
    .dark .text-gray-700 { color: #e2e8f0 !important; }
    .dark .text-gray-900 { color: #f1f5f9 !important; }

    /* Dark Mode Card Specifics */
    .dark .bg-gray-50 { background-color: rgba(31, 41, 55, 0.5) !important; }
    .dark .border-gray-200 { border-color: rgba(75, 85, 99, 0.3) !important; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New User</h1>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Profile Photo -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Profile Photo
                        </label>
                        <div class="mt-2 flex items-center">
                            <span class="inline-block h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <div class="ml-4">
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                                <label for="profile_photo" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Change
                                </label>
                                <p class="mt-2 text-xs text-gray-500">
                                    JPG, GIF or PNG. Max size of 2MB
                                </p>
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
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
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
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
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" 
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
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   required>
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
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" 
                                   autocomplete="new-password"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   required>
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
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                    <option value="{{ $value }}" {{ old('gender') == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                            <input type="text" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" 
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md datepicker"
                                   autocomplete="off">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number
                        </label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
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
                            <textarea id="bio" name="bio" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('bio') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">A short bio about the user.</p>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i> Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize native HTML5 date picker
    const datepickerEl = document.getElementById('date_of_birth');
    if (datepickerEl) {
        // Set max date to today (18 years ago minimum)
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        datepickerEl.max = maxDate.toISOString().split('T')[0];
        
        // Add date change listener
        datepickerEl.addEventListener('change', function() {
            console.log('Date selected:', this.value);
        });
    }

    // Toggle password visibility
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        
        // Toggle icon
        const button = field.parentElement.querySelector('button');
        const icon = button.querySelector('i');
        if (type === 'password') {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    // Preview profile photo
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'h-20 w-20 rounded-full object-cover';
                
                const previewContainer = document.querySelector('.inline-block.h-20.w-20');
                previewContainer.innerHTML = '';
                previewContainer.appendChild(preview);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
