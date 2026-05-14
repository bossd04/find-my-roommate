@extends('admin.layouts.app')

@section('title', 'Create Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                <i class="fas fa-user-plus mr-3 text-purple-600"></i>Create Super Admin
            </h1>
            <p class="mt-2 text-base font-medium text-gray-500 dark:text-gray-400">Add a new administrator to the system.</p>
        </div>
        <a href="{{ route('admin.superadmin.admins') }}"
           class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-900 dark:text-white text-sm font-bold uppercase tracking-wider rounded-xl transition-all duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back to Admins
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden max-w-3xl">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <h3 class="text-lg font-black text-gray-900 dark:text-white">Admin Information</h3>
        </div>
        
        <form action="{{ route('admin.superadmin.store-admin') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        First Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    @error('first_name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        Last Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    @error('last_name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                    Email Address <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                @error('email')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                    Phone Number
                </label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                @error('phone')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required minlength="8"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <p class="mt-1 text-xs text-gray-400">Minimum 8 characters</p>
                    @error('password')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
            </div>

            <!-- Super Admin Checkbox -->
            <div class="p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_superadmin" value="1" {{ old('is_superadmin') ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500">
                    <div class="ml-3">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Grant Super Admin Privileges</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Super admins can create other admins, manage all system settings, and access advanced controls.
                        </p>
                    </div>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.superadmin.admins') }}" 
                   class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-bold uppercase tracking-wider rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-user-plus mr-2"></i>Create Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
