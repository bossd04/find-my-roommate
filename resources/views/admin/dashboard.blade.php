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
        @keyframes barRise {
            from { height: 0%; opacity: 0; }
            to { opacity: 1; }
        }
        .animate-bar {
            animation: barRise 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Responsive Contrast System */
        .high-contrast-title { color: #020617 !important; }
        .high-contrast-subtext { color: #374151 !important; }
        .high-contrast-link { color: #4f46e5 !important; }

        /* Dark Mode Transitions */
        .dark .high-contrast-title { 
            color: #ffffff !important; 
            text-shadow: 0 0 30px rgba(255,255,255,0.1);
        }
        .dark .high-contrast-subtext { 
            color: #e2e8f0 !important; 
        }
        .dark .high-contrast-link { 
            color: #a5b4fc !important; 
        }

        /* Forced Dark Theme for Specific Cards per user request */
        .premium-dark-card { 
            background: rgba(15, 23, 42, 0.95) !important; 
            backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important; 
            color: #ffffff !important; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }
        .premium-dark-card .high-contrast-title { color: #ffffff !important; }
        .premium-dark-card .high-contrast-subtext { color: #cbd5e1 !important; font-weight: 600 !important; }
        .premium-dark-card .high-contrast-link { color: #818cf8 !important; }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
        <div>
            <h1 class="text-3xl font-black tracking-tight high-contrast-title">Admin Dashboard</h1>
            <p class="mt-2 text-base font-medium high-contrast-subtext">Welcome back, Admin! Here's what's happening with your platform.</p>
        </div>
        <div class="mt-6 md:mt-0 text-[10px] uppercase tracking-widest font-bold text-gray-400 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm">
            <i class="far fa-clock mr-2 text-indigo-500"></i>
            Last updated: {{ now()->format('M j, Y, g:i a') }}
        </div>
    </div>

    <!-- Active Alerts / Notifications (Top Banner Style) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Pending Approvals Banner -->
        <a href="{{ route('admin.users.pending-approvals') }}" class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl border-2 border-amber-100/50 dark:border-amber-900/20 shadow-sm hover:shadow-xl hover:border-amber-400 transition-all duration-300">
            <div class="flex items-center space-x-6">
                <div class="p-4 bg-amber-50 dark:bg-amber-900/30 rounded-2xl shadow-inner group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-clock text-2xl text-amber-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold text-amber-700 dark:text-amber-500 uppercase tracking-widest mb-1">Pending Approvals</h4>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $pendingApprovalsCount }}</span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Users waiting for approval</span>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-amber-200 group-hover:text-amber-400 transition-colors"></i>
            </div>
        </a>

        <!-- ID Verification Banner -->
        <a href="{{ route('admin.users.id-verification') }}" class="group relative overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl border-2 border-indigo-100/50 dark:border-indigo-900/20 shadow-sm hover:shadow-xl hover:border-indigo-400 transition-all duration-300">
            <div class="flex items-center space-x-6">
                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl shadow-inner group-hover:scale-110 transition-transform">
                    <i class="fas fa-id-card text-2xl text-indigo-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-500 uppercase tracking-widest mb-1">ID Verification</h4>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $pendingIdVerificationsCount }}</span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending identity verifications</span>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-indigo-200 group-hover:text-indigo-400 transition-colors"></i>
            </div>
        </a>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Registered -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="p-3 bg-indigo-500/10 rounded-xl">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold {{ $userGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center justify-end">
                        <i class="fas fa-arrow-{{ $userGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($userGrowth) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Growth</span>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-none">Total Registered</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-gray-50 mt-1">{{ number_format($userCount) }}</h3>
            <p class="text-xs font-bold text-indigo-500 mt-4">+{{ $newUsersThisMonth }} this month</p>
        </div>

        <!-- Active Users -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="p-3 bg-emerald-500/10 rounded-xl">
                    <i class="fas fa-user-check text-emerald-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-emerald-500">{{ $retentionRate }}%</span>
                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Retention</span>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-none">Active (30 days)</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-gray-50 mt-1">{{ number_format($activeUserCount) }}</h3>
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-4 italic">Of {{ number_format($userCount) }} total users</p>
        </div>

        <!-- All Room Listings -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="p-3 bg-amber-500/10 rounded-xl">
                    <i class="fas fa-home text-amber-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold {{ $listingGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center justify-end">
                        <i class="fas fa-arrow-{{ $listingGrowth >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($listingGrowth) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">New Listings</span>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-none">All Room Listings</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-gray-50 mt-1">{{ number_format($listingCount) }}</h3>
            <p class="text-xs font-bold text-amber-500 mt-4">+{{ $newListingsThisMonth }} this month</p>
        </div>

        <!-- Total Messages -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="p-3 bg-purple-500/10 rounded-xl">
                    <i class="fas fa-envelope text-purple-600 text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-purple-500">{{ $unreadMessagesCount }}</span>
                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Unread</span>
                </div>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest leading-none">Total Messages</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-gray-50 mt-1">{{ number_format($messageCount) }}</h3>
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-4 leading-relaxed">Across all user conversations</p>
        </div>
    </div>

    <!-- Performance Metrics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <!-- User Registration Performance -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-50">Registration Performance</h3>
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl">
                    <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Daily Rate</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-50">{{ $dailyRegistrationRate }}/day</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Retention Rate</span>
                    <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $retentionRate }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Peak Day</span>
                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">
                        @if($peakRegistrationDay)
                            {{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$peakRegistrationDay->day_of_week] }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Monthly Growth</span>
                    <span class="text-sm font-black {{ $userGrowth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $userGrowth >= 0 ? '+' : '' }}{{ $userGrowth }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- User Growth Analytics -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-50">User Growth Analytics</h3>
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl">
                    <i class="fas fa-users text-emerald-600 dark:text-emerald-400"></i>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total Active</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-50">{{ number_format($activeUserCount) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">New This Month</span>
                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ $newUsersThisMonth }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Avg Listings/User</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-50">{{ $avgListingsPerUser }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Avg Messages/User</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-50">{{ $avgMessagesPerUser }}</span>
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-50">Monthly Trends</h3>
                <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-xl">
                    <i class="fas fa-chart-bar text-amber-600 dark:text-amber-400"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($monthlyTrends as $trend)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest w-12">{{ $trend['month'] }}</span>
                        <div class="flex-1 mx-4">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full" style="width: {{ min(100, ($trend['registrations'] / max(1, collect($monthlyTrends)->max('registrations'))) * 100) }}%"></div>
                            </div>
                        </div>
                        <span class="text-xs font-black text-gray-900 dark:text-gray-50 w-8 text-right">{{ $trend['registrations'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Enhanced Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-gray-50">User Registrations Overview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daily new user registrations</p>
                </div>
                <div class="text-right">
                    @php
                        $totalThisWeek = array_sum($registrationChart['data']);
                        $previousWeekTotal = 0;
                        for ($i = 13; $i >= 7; $i--) {
                            $previousWeekTotal += \App\Models\User::whereDate('created_at', now()->subDays($i)->toDateString())->count();
                        }
                        $weekGrowth = $previousWeekTotal > 0 ? round((($totalThisWeek - $previousWeekTotal) / $previousWeekTotal) * 100, 1) : ($totalThisWeek > 0 ? 100 : 0);
                    @endphp
                    <div class="flex items-center justify-end space-x-2">
                        <span class="text-2xl font-black {{ $weekGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                            {{ $weekGrowth >= 0 ? '+' : '' }}{{ $weekGrowth }}%
                        </span>
                        <i class="fas fa-arrow-{{ $weekGrowth >= 0 ? 'up' : 'down' }} {{ $weekGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">vs last week</span>
                </div>
            </div>
            
            <div class="h-64 flex items-end justify-between space-x-4 px-2 bg-gray-50/30 dark:bg-gray-900/10 rounded-3xl p-6 border border-gray-100/50 dark:border-gray-800/50">
                @foreach($registrationChart['labels'] as $index => $label)
                    @php
                        $value = $registrationChart['data'][$index];
                        $max = collect($registrationChart['data'])->max();
                        $max = $max > 0 ? $max : 1;
                        $height = $max > 0 ? ($value / $max) * 100 : 0;
                        $hasData = $value > 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group cursor-pointer h-full justify-end">
                        <!-- Bar Track -->
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-t-2xl relative transition-all duration-300 flex items-end overflow-hidden h-full">
                            <div class="w-full bg-gradient-to-t from-indigo-600 via-indigo-500 to-violet-400 rounded-t-2xl relative transition-all duration-700 ease-out shadow-lg shadow-indigo-500/20 animate-bar" 
                                 style="height: {{ max($hasData ? 10 : 0, $height) }}%; animation-delay: {{ $index * 100 }}ms;">
                                <!-- Glass effect on bar -->
                                <div class="absolute inset-0 bg-white/10 backdrop-blur-[1px]"></div>
                                
                                <!-- Tooltip effect -->
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-black px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap z-20">
                                    {{ $value }} Users
                                </div>
                            </div>
                        </div>
                        
                        <!-- Value Label -->
                        <div class="mt-3 flex flex-col items-center">
                            <span class="text-xs font-black high-contrast-title">{{ $value }}</span>
                            <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tighter mt-1">{{ $label }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary Stats Row -->
            <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">This Week: <strong class="text-gray-900 dark:text-white">{{ $totalThisWeek }}</strong></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Last Week: <strong class="text-gray-900 dark:text-white">{{ $previousWeekTotal }}</strong></span>
                    </div>
                </div>
                <div class="text-xs text-gray-400">
                    Peak: <strong class="text-gray-700 dark:text-gray-300">{{ collect($registrationChart['data'])->max() }}</strong> users/day
                </div>
            </div>
        </div>

        <!-- Summary Stats Card -->
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col">
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-8 leading-none">Data Analytics</h3>
            
            <div class="space-y-10 flex-1">
                <div class="group">
                    <div class="flex justify-between items-end mb-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest transition-colors group-hover:text-indigo-500">Avg. Listings per User</span>
                        <span class="text-xl font-black text-gray-900 dark:text-white">{{ $avgListingsPerUser }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ min(100, $avgListingsPerUser * 20) }}%"></div>
                    </div>
                </div>

                <div class="group">
                    <div class="flex justify-between items-end mb-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest transition-colors group-hover:text-purple-500">Avg. Messages per User</span>
                        <span class="text-xl font-black text-gray-900 dark:text-white">{{ $avgMessagesPerUser }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-purple-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ min(100, $avgMessagesPerUser * 10) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Mini Pulse Indicators -->
            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50/50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-600">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pulse</p>
                    <p class="text-lg font-black text-emerald-500">Stable</p>
                </div>
                <div class="p-4 bg-gray-50/50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-600">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sync</p>
                    <p class="text-lg font-black text-indigo-500">Live</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <a href="{{ route('admin.settings.index') }}" class="group relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-md p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-emerald-500/10 text-emerald-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cog text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white leading-none">Settings</h3>
                    <p class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-widest">System Config</p>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-125 transition-transform duration-700">
                <i class="fas fa-cog text-8xl text-indigo-900"></i>
            </div>
        </a>

        <a href="{{ route('admin.settings.design') }}" class="group relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-md p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 border-l-4 border-l-indigo-500">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-indigo-500/10 text-indigo-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-palette text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white leading-none">Design</h3>
                    <p class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Theme & Styles</p>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-125 transition-transform duration-700">
                <i class="fas fa-palette text-8xl text-indigo-900"></i>
            </div>
        </a>

        <a href="{{ route('admin.listings.index') }}" class="group relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-md p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-purple-500/10 text-purple-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-home text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white leading-none">Listings</h3>
                    <p class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Property Hub</p>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-125 transition-transform duration-700">
                <i class="fas fa-home text-8xl text-indigo-900"></i>
            </div>
        </a>

        <a href="{{ route('admin.messages.index') }}" class="group relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-md p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="p-4 rounded-2xl bg-amber-500/10 text-amber-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-envelope text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white leading-none">Messages</h3>
                    <p class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-widest">In-App Chat</p>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-125 transition-transform duration-700">
                <i class="fas fa-envelope text-8xl text-indigo-900"></i>
            </div>
        </a>
    </div>

    <!-- Recent Activities Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <!-- Recent Users -->
        <div class="premium-dark-card backdrop-blur-md rounded-3xl border shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black tracking-tight high-contrast-title">Recent Users</h2>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1 high-contrast-subtext">Newly Registered Members</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-black uppercase tracking-widest hover:text-indigo-800 transition-colors high-contrast-link">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($recentUsers as $user)
                    <div class="px-8 py-5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center font-black text-white shadow-lg shadow-indigo-500/20">
                                {{ substr($user->first_name ?? '', 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}
                            </div>
                            <div class="ml-5">
                                <h3 class="text-sm font-black leading-tight high-contrast-title">{{ $user->full_name ?? 'Unknown User' }}</h3>
                                <p class="text-[11px] font-bold high-contrast-subtext">{{ $user->email ?? 'No email' }}</p>
                            </div>
                            <div class="ml-auto text-right">
                                <p class="text-[10px] font-black uppercase tracking-tighter high-contrast-subtext">{{ $user->created_at?->diffForHumans() ?? 'Unknown time' }}</p>
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-white/10 text-indigo-300 mt-1">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400 font-bold uppercase tracking-widest">No recent users found.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Listings -->
        @if(isset($recentListings) && $recentListings->count() > 0)
            <div class="premium-dark-card backdrop-blur-md rounded-3xl border shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-white/10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black tracking-tight high-contrast-title">Recent Listings</h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1 high-contrast-subtext">New Room Opportunities</p>
                    </div>
                    <a href="{{ route('admin.listings.index') }}" class="text-[10px] font-black uppercase tracking-widest hover:text-indigo-800 transition-colors high-contrast-link">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($recentListings as $listing)
                        <div class="px-8 py-5 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <div class="flex items-center">
                                <div class="h-14 w-14 rounded-2xl bg-gray-100 dark:bg-gray-900 overflow-hidden shadow-inner border border-gray-200/50 dark:border-gray-700">
                                    @if($listing->images->first())
                                        <img src="{{ route('listing.image.serve', ['filename' => basename($listing->images->first()->path)]) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <i class="fas fa-home text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-5 flex-1">
                                    <h3 class="text-sm font-black leading-tight truncate max-w-[200px] high-contrast-title">{{ $listing->title ?? 'Untitled Listing' }}</h3>
                                    <p class="text-[10px] font-bold uppercase mt-1 high-contrast-subtext">{{ $listing->property_type ?? 'Unknown' }} • {{ $listing->location ?? 'Unknown Location' }}</p>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="text-[11px] font-black text-indigo-600">${{ number_format($listing->price ?? 0) }}</p>
                                    <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $listing->is_active ? 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600' : 'bg-gray-50 text-gray-400' }} mt-1">
                                        {{ $listing->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- System & Environment Info -->
    <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700">
            <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">System Infrastructure</h2>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Environment & Core Statistics</p>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Stack Details -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Application Stack</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">Laravel</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">PHP</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ phpversion() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Database Details -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Database Engine</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">Connection</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white uppercase">{{ config('database.default') }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">Migrations</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ DB::table('migrations')->count() }} run</span>
                        </div>
                    </div>
                </div>

                <!-- Server Info -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Server Runtime</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">Memory</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white">{{ ini_get('memory_limit') }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50 px-4 py-2 rounded-xl">
                            <span class="text-[11px] font-bold text-gray-500 uppercase">Env</span>
                            <span class="text-xs font-black text-emerald-500 uppercase">{{ app()->environment() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Admin Actions -->
                <div class="space-y-4">
                    <p class="text-[10px] font-black high-contrast-subtext uppercase tracking-widest">System Maintenance</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.backup.index') }}" class="p-3 bg-indigo-600 text-white rounded-xl text-center hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-database text-xs"></i>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="p-3 bg-white rounded-xl text-center hover:bg-gray-100 transition-colors shadow-lg shadow-gray-500/20">
                            <i class="fas fa-tools text-xs text-slate-900"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimal Global Footer -->
    <div class="pb-12 text-center">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">Find My Roommate © {{ date('Y') }}</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Defensive programming to prevent null reference errors
    try {
        // Handle any potential null references in dashboard
        const initializeDashboard = () => {
            // Safe DOM element access
            const safeQuerySelector = (selector) => {
                try {
                    return document.querySelector(selector);
                } catch (e) {
                    console.warn('Invalid selector:', selector);
                    return null;
                }
            };
            
            // Safe array operations
            const safeArrayContains = (array, item) => {
                return Array.isArray(array) ? array.includes(item) : false;
            };
            
            // Initialize any dashboard components safely
            console.log('Dashboard initialized successfully');
        };
        
        // Initialize dashboard when DOM is ready
        initializeDashboard();
        
    } catch (error) {
        console.error('Dashboard initialization error:', error);
        // Prevent the error from breaking the page
    }
});
</script>
@endpush
