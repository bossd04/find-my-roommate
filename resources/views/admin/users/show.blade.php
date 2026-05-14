@extends('admin.layouts.app')

@section('title', 'User Details: ' . $user->name)

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

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
    .dark .text-gray-400 { color: #818cf8 !important; } /* Make them slightly tinted for better look */
    .dark .text-gray-600 { color: #cbd5e1 !important; }
    .dark .text-gray-700 { color: #e2e8f0 !important; }
    .dark .text-gray-800 { color: #f1f5f9 !important; }

    /* Dark Mode Card Specifics */
    .dark .bg-gray-50 { background-color: rgba(31, 41, 55, 0.5) !important; }
    .dark .bg-red-50 { background-color: rgba(127, 29, 29, 0.2) !important; }
    .dark .bg-blue-50 { background-color: rgba(30, 58, 138, 0.2) !important; }
    .dark .bg-indigo-50 { background-color: rgba(49, 46, 129, 0.2) !important; }
    .dark .bg-purple-50 { background-color: rgba(81, 33, 99, 0.2) !important; }
    .dark .border-gray-200, .dark .border-red-200 { border-color: rgba(75, 85, 99, 0.3) !important; }
    .dark .text-red-800 { color: #fca5a5 !important; }
    .dark .text-red-600 { color: #f87171 !important; }
    .dark .border-white { border-color: rgba(255, 255, 255, 0.1) !important; }
    .dark .bg-gray-100 { background-color: rgba(75, 85, 99, 0.2) !important; }
    .dark .text-gray-900 { color: #f1f5f9 !important; }
    .dark .ring-white { --tw-ring-color: rgba(15, 23, 42, 1) !important; }
    .dark .bg-blue-100 { background-color: rgba(30, 58, 138, 0.3) !important; }
    .dark .bg-indigo-100 { background-color: rgba(49, 46, 129, 0.3) !important; }
    .dark .bg-purple-100 { background-color: rgba(81, 33, 99, 0.3) !important; }
    .dark .text-indigo-600 { color: #a5b4fc !important; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with back button -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">User Details</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <a href="{{ route('admin.users.index') }}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600">Users</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $user->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-white/90 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                        @if(!empty($user->avatar))
                            <img src="{{ route('avatar.serve', ['filename' => basename($user->avatar)]) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @elseif(!empty($user->profile_photo_path))
                            <img src="{{ route('profile.photo.serve', ['filename' => basename($user->profile_photo_path)]) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-indigo-100">
                                <span class="text-2xl font-bold text-indigo-600">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                        <div class="flex items-center mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->is_admin ? 'Administrator' : 'Standard User' }}
                            </span>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($user->email_verified_at)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @endif
                            @if($user->last_seen && $user->last_seen->diffInMinutes() < 5)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <span class="h-2 w-2 rounded-full bg-green-500 mr-1"></span> Online
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-1"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    @endif
                    <a href="mailto:{{ $user->email }}" 
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-envelope mr-1"></i> Send Email
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="border-t border-gray-200">
            <dl class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                <div class="px-4 py-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->created_at->format('F j, Y') }}
                        <span class="text-gray-500 text-xs">({{ $user->created_at->diffForHumans() }})</span>
                    </dd>
                </div>
                <div class="px-4 py-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Active</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->last_seen)
                            {{ $user->last_seen->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                    <dd class="mt-1">
                        @if($user->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="lg:col-span-2">
            <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Personal Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Basic details and contact information.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->full_name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="ml-2 text-green-500" title="Verified">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="ml-2 text-yellow-500" title="Not Verified">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?: 'Not provided' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('F j, Y') : 'Not provided' }}
                                @if($user->date_of_birth)
                                    <span class="text-gray-500 text-xs">({{ $user->age }} years old)</span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->gender)
                                    {{ ucfirst($user->gender) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Bio</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->bio ?: 'No bio provided.' }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="px-4 py-4 sm:px-6 border-t border-gray-200 bg-gray-50 text-right">
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Education Information -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Education Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Academic background and studies.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">University</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->university ?: 'Not provided' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Course/Major</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->course ?: 'Not provided' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Year Level</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->year_level ?: 'Not provided' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->department ?: 'Not provided' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Study Habits</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->study_habit)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->study_habit)) }}
                                @elseif($user->preference && $user->preference->study_habit)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->study_habit)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->occupation ?: 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Location Information -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Location Information
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Current location and housing preferences.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Current Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->apartment_location)
                                    {{ $user->roommateProfile->apartment_location }}
                                @elseif($user->location)
                                    {{ $user->location }}
                                @else
                                    Not provided
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">City</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->city)
                                    {{ $user->roommateProfile->city }}
                                @else
                                    Not provided
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Budget Range</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->budget_min || $user->budget_max)
                                    ₱{{ number_format($user->budget_min ?: 0, 0) }} - ₱{{ number_format($user->budget_max ?: 0, 0) }}
                                @elseif($user->roommateProfile && ($user->roommateProfile->budget_min || $user->roommateProfile->budget_max))
                                    ₱{{ number_format($user->roommateProfile->budget_min ?: 0, 0) }} - ₱{{ number_format($user->roommateProfile->budget_max ?: 0, 0) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Has Apartment</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile)
                                    {{ $user->roommateProfile->has_apartment ? 'Yes' : 'No' }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Preferred Move-in Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->move_in_date)
                                    {{ \Carbon\Carbon::parse($user->roommateProfile->move_in_date)->format('F j, Y') }}
                                @elseif($user->move_in_date)
                                    {{ \Carbon\Carbon::parse($user->move_in_date)->format('F j, Y') }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Lease Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->lease_duration)
                                    {{ $user->roommateProfile->lease_duration }}
                                @elseif($user->preferred_lease_length)
                                    {{ $user->preferred_lease_length }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Lifestyle Preferences -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Lifestyle Preferences
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Living habits and preferences.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Cleanliness Level</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->cleanliness_level)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->cleanliness_level)) }}
                                @elseif($user->preference && $user->preference->cleanliness_level)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->cleanliness_level)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Noise Level</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->noise_level)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->noise_level)) }}
                                @elseif($user->preference && $user->preference->noise_tolerance)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->noise_tolerance)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Daily Schedule</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->schedule)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->schedule)) }}
                                @elseif($user->preference && $user->preference->schedule)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->schedule)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Sleep Pattern</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->sleep_pattern)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->sleep_pattern)) }}
                                @elseif($user->preference && $user->preference->sleep_pattern)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->sleep_pattern)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Smoking Allowed</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile)
                                    {{ $user->roommateProfile->smoking_allowed ? 'Yes' : 'No' }}
                                @elseif($user->preference && $user->preference->smoking !== null)
                                    {{ $user->preference->smoking === 'never' ? 'No' : 'Yes' }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Pets Allowed</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile)
                                    {{ $user->roommateProfile->pets_allowed ? 'Yes' : 'No' }}
                                @elseif($user->preference && $user->preference->pets !== null)
                                    {{ $user->preference->pets === 'none' ? 'No' : 'Yes' }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Additional Preferences -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Additional Preferences
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Other preferences and lifestyle information.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Preferred Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->preferred_location ?: 'Not specified' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Noise Tolerance</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->noise_tolerance)
                                    {{ ucfirst(str_replace('_', ' ', $user->roommateProfile->noise_tolerance)) }}
                                @elseif($user->preference && $user->preference->noise_tolerance)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->noise_tolerance)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Lifestyle Tags</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->lifestyle_tags)
                                    @if(is_array($user->roommateProfile->lifestyle_tags))
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($user->roommateProfile->lifestyle_tags as $tag)
                                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ is_array($user->roommateProfile->lifestyle_tags) ? implode(', ', $user->roommateProfile->lifestyle_tags) : $user->roommateProfile->lifestyle_tags }}
                                    @endif
                                @else
                                    No lifestyle tags specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Overnight Visitors</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->preference && $user->preference->overnight_visitors)
                                    {{ ucfirst(str_replace('_', ' ', $user->preference->overnight_visitors)) }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Display Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->roommateProfile && $user->roommateProfile->display_name)
                                    {{ $user->roommateProfile->display_name }}
                                @else
                                    Not specified
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Hobbies & Interests -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Hobbies & Interests
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Activities and interests.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="text-sm text-gray-900">
                        @if($user->hobbies)
                            @if(is_array($user->hobbies))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->hobbies as $hobby)
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">{{ $hobby }}</span>
                                    @endforeach
                                </div>
                            @else
                                {{ $user->hobbies }}
                            @endif
                        @elseif($user->roommateProfile && $user->roommateProfile->hobbies)
                            @if(is_array($user->roommateProfile->hobbies))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roommateProfile->hobbies as $hobby)
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">{{ $hobby }}</span>
                                    @endforeach
                                </div>
                            @else
                                {{ is_array($user->roommateProfile->hobbies) ? implode(', ', $user->roommateProfile->hobbies) : $user->roommateProfile->hobbies }}
                            @endif
                        @elseif($user->preference && $user->preference->hobbies)
                            @if(is_array($user->preference->hobbies))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->preference->hobbies as $hobby)
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">{{ $hobby }}</span>
                                    @endforeach
                                </div>
                            @else
                                {{ is_array($user->preference->hobbies) ? implode(', ', $user->preference->hobbies) : $user->preference->hobbies }}
                            @endif
                        @else
                            No hobbies specified
                        @endif
                    </div>
                </div>
            </div>

            <!-- ID Verification Status -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        ID Verification Status
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Identity verification information.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @php
                        $hasFrontId = $user->id_card_front || ($user->userValidation && $user->userValidation->id_front_image);
                        $hasBackId = $user->id_card_back || ($user->userValidation && $user->userValidation->id_back_image);
                        $hasAnyIdDocs = $hasFrontId || $hasBackId;
                        $hasValidation = $user->userValidation !== null;
                    @endphp
                    
                    @if($hasValidation)
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">ID Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->userValidation->id_type ?: 'Not specified' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->userValidation->id_number ?: 'Not specified' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                                <dd class="mt-1">
                                    @if($user->userValidation->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Approved
                                        </span>
                                    @elseif($user->userValidation->status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Submitted Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->userValidation->created_at->format('F j, Y') }}</dd>
                            </div>
                        </dl>
                    @endif
                    
                    @if($hasAnyIdDocs)
                        <div class="@if($hasValidation) mt-6 @endif">
                            <dt class="text-sm font-medium text-gray-500 mb-3">ID Documents</dt>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($hasFrontId)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Front of ID</p>
                                        @if($user->id_card_front)
                                            <a href="{{ route('id.card.serve', ['path' => $user->id_card_front]) }}" target="_blank">
                                                <img src="{{ route('id.card.serve', ['path' => $user->id_card_front]) }}" 
                                                     alt="Front of ID" 
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer">
                                            </a>
                                        @elseif($user->userValidation && $user->userValidation->id_front_image)
                                            <a href="{{ asset('storage/' . $user->userValidation->id_front_image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $user->userValidation->id_front_image) }}" 
                                                     alt="Front of ID" 
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer">
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                @if($hasBackId)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Back of ID</p>
                                        @if($user->id_card_back)
                                            <a href="{{ route('id.card.serve', ['path' => $user->id_card_back]) }}" target="_blank">
                                                <img src="{{ route('id.card.serve', ['path' => $user->id_card_back]) }}" 
                                                     alt="Back of ID" 
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer">
                                            </a>
                                        @elseif($user->userValidation && $user->userValidation->id_back_image)
                                            <a href="{{ asset('storage/' . $user->userValidation->id_back_image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $user->userValidation->id_back_image) }}" 
                                                     alt="Back of ID" 
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer">
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Click on images to view full size</p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-id-card text-gray-300 text-4xl mb-2"></i>
                            <p class="text-gray-500">No ID verification submitted yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="mt-6 bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Recent Activity
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        User's recent actions and system events.
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if($activities->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($activities as $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas {{ $activity->get_icon }} text-white text-sm"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            {!! $activity->description !!}
                                                            <span class="font-medium text-gray-900">{{ $activity->causer->name ?? 'System' }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View all activity<span aria-hidden="true"> &rarr;</span>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-300 text-4xl mb-2"></i>
                            <p class="text-gray-500">No recent activity found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Status -->
            <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Account Status
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Email Verification</span>
                                @if($user->email_verified_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </div>
                            @if(!$user->email_verified_at)
                                <p class="mt-1 text-xs text-gray-500">Email not verified yet.</p>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Account Status</span>
                                @if($user->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                @if($user->is_active)
                                    User can log in and use the platform.
                                @else
                                    User cannot log in to the platform.
                                @endif
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Last Login</span>
                                <span class="text-xs text-gray-500">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                IP: {{ $user->last_login_ip ?: 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @if($user->id !== auth()->id())
                        <div class="mt-6">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i>
                                    {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Statistics -->
            <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        User Statistics
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Member for</h4>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ $user->created_at->diffInDays() }} days
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Since {{ $user->created_at->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <i class="fas fa-home text-indigo-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-500">Listings</p>
                                        <p class="text-xl font-semibold text-gray-900">{{ $user->listings_count ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-comment-alt text-purple-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-500">Messages</p>
                                        <p class="text-xl font-semibold text-gray-900">{{ $user->messages_count ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($user->id !== auth()->id())
                <div class="bg-white/90 shadow overflow-hidden sm:rounded-lg border border-red-200">
                    <div class="px-4 py-5 sm:px-6 border-b border-red-200 bg-red-50">
                        <h3 class="text-lg leading-6 font-medium text-red-800">
                            Danger Zone
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-red-800">Delete User Account</h4>
                                <p class="mt-1 text-sm text-red-600">
                                    Once you delete a user's account, there is no going back. Please be certain.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        id="open-delete-modal-btn"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                    <i class="fas fa-trash mr-2"></i> Delete User Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<!-- Delete Confirmation Modal -->
<div id="delete-confirm-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div id="delete-modal-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <!-- Modal Card -->
    <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full p-8 border border-red-100 dark:border-red-900/40 animate-fade-in">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-5">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Delete User Account?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                This will permanently delete <strong class="text-gray-800 dark:text-gray-200">{{ $user->full_name }}</strong> and all their data.
            </p>
            <p class="text-xs text-red-500 dark:text-red-400 font-semibold uppercase tracking-widest mb-8">This action cannot be undone.</p>
            <div class="flex gap-3 w-full">
                <button id="cancel-delete-btn"
                        class="flex-1 py-3 px-4 rounded-xl border border-gray-300 dark:border-gray-600 text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                    Cancel
                </button>
                <button id="confirm-delete-btn"
                        class="flex-1 py-3 px-4 rounded-xl text-sm font-black text-white bg-red-600 hover:bg-red-700 transition-all shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                    <i class="fas fa-trash text-xs"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="action-toast" class="fixed bottom-6 right-6 z-[9999] hidden items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl min-w-[280px] max-w-sm" role="alert">
    <div id="toast-icon" class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center">
        <i id="toast-icon-i" class="text-sm"></i>
    </div>
    <div>
        <p id="toast-title" class="text-xs font-black uppercase tracking-widest"></p>
        <p id="toast-message" class="text-sm font-medium mt-0.5"></p>
    </div>
</div>

@push('scripts')
<script>
    const deleteRoute   = @json(route('admin.users.destroy', $user));
    const usersListUrl  = @json(route('admin.users.index'));
    const csrfToken     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Modal helpers ────────────────────────────────────────────────
    const modal     = document.getElementById('delete-confirm-modal');
    const backdrop  = document.getElementById('delete-modal-backdrop');

    function openDeleteModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('open-delete-modal-btn').addEventListener('click', openDeleteModal);
    document.getElementById('cancel-delete-btn').addEventListener('click', closeDeleteModal);
    backdrop.addEventListener('click', closeDeleteModal);

    // ── Toast helper ─────────────────────────────────────────────────
    function showToast(type, title, message) {
        const toast   = document.getElementById('action-toast');
        const iconEl  = document.getElementById('toast-icon');
        const iconI   = document.getElementById('toast-icon-i');
        const titleEl = document.getElementById('toast-title');
        const msgEl   = document.getElementById('toast-message');

        if (type === 'success') {
            toast.className  = toast.className.replace(/bg-\S+/g, '');
            toast.classList.add('bg-emerald-600', 'text-white');
            iconEl.className = 'flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500/30 flex items-center justify-center';
            iconI.className  = 'fas fa-check text-white text-sm';
        } else {
            toast.className  = toast.className.replace(/bg-\S+/g, '');
            toast.classList.add('bg-red-600', 'text-white');
            iconEl.className = 'flex-shrink-0 w-8 h-8 rounded-full bg-red-500/30 flex items-center justify-center';
            iconI.className  = 'fas fa-times text-white text-sm';
        }

        titleEl.textContent = title;
        msgEl.textContent   = message;

        toast.classList.remove('hidden');
        toast.classList.add('flex');

        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 4000);
    }

    // ── AJAX Delete ──────────────────────────────────────────────────
    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        const btn = this;
        btn.disabled    = true;
        btn.innerHTML   = '<i class="fas fa-spinner fa-spin text-xs mr-1"></i> Deleting…';

        const formData = new FormData();
        formData.append('_token',  csrfToken);
        formData.append('_method', 'DELETE');

        fetch(deleteRoute, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                closeDeleteModal();

                if (data.success) {
                    showToast('success', 'Account Deleted', data.message || 'User has been permanently deleted.');
                    // Store message for the index page
                    sessionStorage.setItem('admin_flash_success', data.message || 'User has been permanently deleted.');
                    setTimeout(() => { window.location.href = usersListUrl; }, 1800);
                } else {
                    showToast('error', 'Error', data.message || 'Could not delete user. Please try again.');
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fas fa-trash text-xs"></i> Yes, Delete';
                }
            })
            .catch(() => {
                closeDeleteModal();
                showToast('error', 'Error', 'A network error occurred. Please try again.');
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-trash text-xs"></i> Yes, Delete';
            });
    });

    // ── Bootstrap tooltips (optional) ────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        }
    });
</script>
@endpush
