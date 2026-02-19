@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }
        .activity-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid #e5e7eb;
        }
        .activity-item:last-child {
            border-left-color: transparent;
        }
        .activity-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: #3b82f6;
            border: 3px solid #ffffff;
        }
        .activity-item:last-child::before {
            background-color: #10b981;
        }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Welcome back, {{ $currentAdmin->first_name ?? 'Admin' }}! Here's what's happening with your platform.</p>
        </div>
        <div class="mt-4 md:mt-0 text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
            <i class="far fa-clock mr-1"></i>
            Last updated: {{ now()->format('F j, Y, g:i a') }}
        </div>
    </div>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden stat-card">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="stat-icon bg-blue-50 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                        +{{ $newUsersThisMonth }} this month
                    </span>
                </div>
                <h3 class="mt-4 text-sm font-medium text-gray-500">Total Users</h3>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($userCount) }}</p>
                <div class="mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-user-check mr-1 text-green-500"></i>
                        <span>{{ $activeUserCount }} active users</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View all users
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Listings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden stat-card">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="stat-icon bg-purple-50 text-purple-600">
                        <i class="fas fa-home text-xl"></i>
                    </div>
                    @if($newListingsThisMonth > 0)
                    <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                        +{{ $newListingsThisMonth }} new
                    </span>
                    @endif
                </div>
                <h3 class="mt-4 text-sm font-medium text-gray-500">Total Listings</h3>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($listingCount) }}</p>
                <div class="mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-chart-line mr-1 text-purple-500"></i>
                        <span>{{ $newListingsThisMonth }} new this month</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <a href="{{ route('admin.listings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View all listings
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden stat-card">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="stat-icon bg-amber-50 text-amber-600">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    @if($unreadMessagesCount > 0)
                    <span class="text-sm font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                        {{ $unreadMessagesCount }} unread
                    </span>
                    @endif
                </div>
                <h3 class="mt-4 text-sm font-medium text-gray-500">Messages</h3>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($messageCount) }}</p>
                <div class="mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        @if($unreadMessagesCount > 0)
                            <i class="fas fa-circle text-red-500 text-xs mr-1"></i>
                            <span>{{ $unreadMessagesCount }} require attention</span>
                        @else
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            <span>All caught up</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <a href="{{ route('admin.messages.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View messages
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden stat-card">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="stat-icon bg-green-50 text-green-600">
                        <i class="fas fa-server text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                        <i class="fas fa-circle animate-pulse mr-1"></i>
                        Live
                    </span>
                </div>
                <h3 class="mt-4 text-sm font-medium text-gray-500">System Status</h3>
                <p class="mt-1 text-2xl font-semibold text-gray-900">Operational</p>
                <div class="mt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        <span>All systems normal</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <a href="{{ route('admin.settings') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    System settings
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- System Messages -->
        <a href="{{ route('admin.messages.index') }}" class="block hover:shadow-md transition-shadow duration-200">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Messages</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $messageCount ?? 0 }}</p>
                        <p class="text-sm text-gray-500">{{ $unreadMessagesCount ?? 0 }} unread</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
        </div>
        <div class="bg-white p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.settings') }}" class="group p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-cog text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Settings</h3>
                        <p class="mt-1 text-sm text-gray-500">Configure system settings</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.listings.index') }}" class="group p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-home text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Listings</h3>
                        <p class="mt-1 text-sm text-gray-500">Manage property listings</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="group p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Messages</h3>
                        <p class="mt-1 text-sm text-gray-500">View and manage system messages</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- System Information -->
    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
                <p class="text-sm text-gray-500">Newly registered users</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentUsers as $user)
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ $user->full_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">
                    No recent users found.
                </div>
                @endforelse
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 text-right">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View all users
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Recent Listings -->
        @if(isset($recentListings) && $recentListings->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Recent Listings</h2>
                <p class="text-sm text-gray-500">Newly added properties</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentListings as $listing)
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md overflow-hidden">
                            @if($listing->images->first())
                                <img src="{{ Storage::url($listing->images->first()->path) }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $listing->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $listing->property_type }} • {{ $listing->location }}</p>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <span class="font-medium text-gray-900">${{ number_format($listing->price) }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $listing->bedrooms }} beds • {{ $listing->bathrooms }} baths</span>
                            </div>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-xs text-gray-500">{{ $listing->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $listing->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $listing->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 text-right">
                <a href="{{ route('admin.listings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View all listings
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">System Information</h2>
            <p class="text-sm text-gray-500">Server status and environment details</p>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Application</h3>
                    <ul class="space-y-2">
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Laravel Version</span>
                            <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">PHP Version</span>
                            <span class="text-sm font-medium text-gray-900">{{ phpversion() }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Environment</span>
                            <span class="text-sm font-medium text-gray-900">{{ app()->environment() }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Debug Mode</span>
                            <span class="text-sm font-medium {{ config('app.debug') ? 'text-green-600' : 'text-gray-900' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">PHP Version</span>
                            <span class="text-sm font-medium text-gray-900">{{ phpversion() }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Environment</span>
                            <span class="text-sm font-medium text-gray-900">{{ app()->environment() }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Database</h3>
                    <ul class="space-y-2">
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Name</span>
                            <span class="text-sm font-medium text-gray-900">{{ config('database.connections.mysql.database') }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Connection</span>
                            <span class="text-sm font-medium text-gray-900">{{ config('database.default') }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Tables</span>
                            <span class="text-sm font-medium text-gray-900">
                                @php
                                    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                                    echo count($tables) . ' tables';
                                @endphp
                            </span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-sm text-gray-500">Migrations</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ DB::table('migrations')->count() }} run
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Server Information -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Server Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Server</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ request()->server('SERVER_SOFTWARE') }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">PHP</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ phpversion() }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Memory Limit</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ ini_get('memory_limit') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150">
                        <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Add New User</h4>
                            <p class="text-xs text-gray-500">Create a new user account</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.listings.create') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150">
                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Add New Listing</h4>
                            <p class="text-xs text-gray-500">Create a new property listing</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150">
                        <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Settings</h4>
                            <p class="text-xs text-gray-500">Configure system settings</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.backup.index') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150">
                        <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Backup</h4>
                            <p class="text-xs text-gray-500">Create system backup</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p class="mt-1 text-xs">Laravel v{{ app()->version() }} | PHP v{{ phpversion() }} | Environment: {{ app()->environment() }}</p>
    </div>
</div>
@endsection
