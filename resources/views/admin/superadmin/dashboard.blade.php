@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')

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
            background-color: #8b5cf6;
            border: 3px solid #ffffff;
        }
        .activity-item:last-child::before {
            background-color: #10b981;
        }
        .high-contrast-title { color: #020617 !important; }
        .high-contrast-subtext { color: #374151 !important; }
        .dark .high-contrast-title { color: #ffffff !important; }
        .dark .high-contrast-subtext { color: #e2e8f0 !important; }
        .superadmin-card {
            background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
            color: #ffffff;
        }
        .superadmin-card .stat-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight high-contrast-title">Super Admin Dashboard</h1>
            <p class="mt-2 text-base font-medium high-contrast-subtext">System overview and administrative controls.</p>
        </div>
        <div class="mt-6 md:mt-0 flex items-center gap-4">
            <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full text-sm font-bold">
                <i class="fas fa-crown mr-2"></i>Super Admin Access
            </span>
            <div class="text-[10px] uppercase tracking-widest font-bold text-gray-400 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm">
                <i class="far fa-clock mr-2 text-purple-500"></i>
                {{ now()->format('M j, Y, g:i a') }}
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Super Admins Card -->
        <div class="stat-card superadmin-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-xs font-black uppercase tracking-widest">Super Admins</p>
                    <p class="text-3xl font-black mt-2">{{ $totalSuperAdmins }}</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-crown text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.superadmin.admins') }}" class="text-white/80 text-xs font-bold hover:text-white transition-colors">
                    View All Admins <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Admins Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Total Admins</p>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ $totalAdmins }}</p>
                </div>
                <div class="stat-icon bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.superadmin.create-admin') }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:text-indigo-800 transition-colors">
                    Create New Admin <i class="fas fa-plus ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Total Users</p>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:text-emerald-800 transition-colors">
                    Manage Users <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Listings Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Total Listings</p>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ number_format($totalListings) }}</p>
                </div>
                <div class="stat-icon bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-home text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.listings.index') }}" class="text-blue-600 dark:text-blue-400 text-xs font-bold hover:text-blue-800 transition-colors">
                    View Listings <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Messages Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Total Messages</p>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ number_format($totalMessages) }}</p>
                </div>
                <div class="stat-icon bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.messages.index') }}" class="text-amber-600 dark:text-amber-400 text-xs font-bold hover:text-amber-800 transition-colors">
                    View Messages <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Quick Actions</p>
                <div class="stat-icon bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400">
                    <i class="fas fa-bolt text-xl"></i>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.superadmin.create-admin') }}" class="block w-full text-left px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-user-plus mr-2 text-purple-500"></i>Add New Admin
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block w-full text-left px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-2 text-gray-500"></i>System Settings
                </a>
                <a href="{{ route('admin.backup.index') }}" class="block w-full text-left px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-database mr-2 text-green-500"></i>Backup System
                </a>
            </div>
        </div>

        <!-- Account Deletion Requests Card -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest">Deletion Requests</p>
                    <p class="text-3xl font-black mt-2 {{ $pendingDeletionCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                        {{ $pendingDeletionCount }}
                        @if($pendingDeletionCount > 0)
                            <span class="text-sm font-medium text-amber-600 dark:text-amber-400 ml-1">pending</span>
                        @endif
                    </p>
                </div>
                <div class="stat-icon {{ $pendingDeletionCount > 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                    <i class="fas fa-user-slash text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.superadmin.deletion-requests') }}" class="text-sm font-bold {{ $pendingDeletionCount > 0 ? 'text-red-600 dark:text-red-400 hover:text-red-800' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800' }} transition-colors">
                    @if($pendingDeletionCount > 0)
                        <i class="fas fa-exclamation-circle mr-1"></i>Review {{ $pendingDeletionCount }} Request{{ $pendingDeletionCount > 1 ? 's' : '' }}
                    @else
                        View All Requests
                    @endif
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @if($pendingDeletionRequests->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Recent Requests</p>
                    <div class="space-y-2">
                        @foreach($pendingDeletionRequests as $request)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                                        @if($request->user->profile_photo_url)
                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->fullName() }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ substr($request->user->first_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-[140px]">{{ $request->user->fullName() }}</span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $request->requested_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-black text-gray-900 dark:text-white">
                <i class="fas fa-history mr-2 text-purple-500"></i>Recent Activity
            </h3>
            <a href="{{ route('admin.activity_logs.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-800">
                View All Logs
            </a>
        </div>
        <div class="p-6">
            @if($recentActivities->count() > 0)
                <div class="space-y-0">
                    @foreach($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $activity->description }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @if($activity->causer)
                                            by {{ $activity->causer->fullName() }}
                                        @else
                                            by System
                                        @endif
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No recent activity</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
