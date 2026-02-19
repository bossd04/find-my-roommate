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
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <a href="{{ route('admin.users.index') }}" class="ml-2 text-sm font-medium text-gray-700 hover:text-indigo-600">Users</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-2 text-sm font-medium text-gray-500">{{ $user->name }}</span>
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
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-white border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
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
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
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
                                {{ $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : 'Not provided' }}
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

            <!-- Activity Log -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
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
                                                        <i class="fas {{ $activity->getIcon() }} text-white text-sm"></i>
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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
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
                <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-red-200">
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
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-2"></i> Delete User Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Any additional JavaScript can go here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any tooltips or other interactive elements
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
