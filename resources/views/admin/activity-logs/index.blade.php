@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Activity Logs</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    System Activity Logs <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $activityLogs->count() ?? 0 }} total)</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <button onclick="window.location.reload()" class="group flex items-center px-6 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-sync-alt mr-3 group-hover:rotate-180 transition-transform duration-500"></i>
                    Refresh
                </button>
                <form method="POST" action="{{ route('admin.activity_logs.clear') }}" class="inline">
                    @csrf
                    <button type="submit" class="group flex items-center px-6 py-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm" onclick="return confirm('Are you sure you want to clear all logs?')">
                        <i class="fas fa-trash-alt mr-3 group-hover:scale-110 transition-transform"></i>
                        Clear All Logs
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Decorative Background Element -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <p class="font-medium font-bold text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(isset($error))
        <div class="bg-amber-100 border-l-4 border-amber-500 text-amber-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 mt-1"></i>
                <p class="font-medium text-sm">{{ $error }}</p>
            </div>
        </div>
    @endif

    <!-- Activity Logs Table -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Timestamp</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">User</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Action</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">IP Address</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Details</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($activityLogs as $log)
                        <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50">
                                    {{ isset($log->created_at) ? \Carbon\Carbon::parse($log->created_at)->format('M j, Y H:i:s') : 'N/A' }}
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ isset($log->created_at) ? \Carbon\Carbon::parse($log->created_at)->diffForHumans() : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm shadow-sm">
                                        @if(isset($log->user_name))
                                            {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                        @elseif(isset($log->user) && $log->user)
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        @elseif(isset($log->causer_id) && $log->causer_id)
                                            U
                                        @else
                                            S
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-black text-gray-900 dark:text-gray-200 leading-tight">
                                            @if(isset($log->user_name))
                                                {{ $log->user_name }}
                                            @elseif(isset($log->user) && $log->user)
                                                {{ $log->user->name }}
                                            @elseif(isset($log->causer_id) && $log->causer_id)
                                                User ID: {{ $log->causer_id }}
                                            @else
                                                System
                                            @endif
                                        </div>
                                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                            @if(isset($log->user) && $log->user)
                                                {{ $log->user->email }}
                                            @else
                                                System Action
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                                    {{ $log->event ?? $log->description ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50">
                                    {{ $log->ip_address ?? '-' }}
                                </div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Network Address
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50 leading-tight">
                                    {{ $log->description ?? '-' }}
                                </div>
                                @if(isset($log->properties) && $log->properties)
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                                        @if(is_string($log->properties))
                                            {{ Str::limit($log->properties, 50) }}
                                        @elseif(is_array($log->properties))
                                            {{ count($log->properties) }} properties changed
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(isset($log->id))
                                        <form method="POST" action="{{ route('admin.activity_logs.destroy', $log->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" onclick="return confirm('Are you sure you want to delete this log entry?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-400 font-bold uppercase tracking-widest">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
