@extends('admin.layouts.app')

@section('title', 'User Reports Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">User Reports</h1>
            <p class="mt-2 text-base font-medium text-gray-600 dark:text-gray-300">Manage and review user-submitted reports</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.user-reports.users') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg shadow-indigo-900/20">
                <i class="fas fa-user-shield mr-2"></i> Reported Users Summary
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-transparent dark:border-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Reports</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg shadow p-4 border border-yellow-200 dark:border-yellow-900/50">
            <div class="text-sm text-yellow-700 dark:text-yellow-400">Pending</div>
            <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-300">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow p-4 border border-blue-200 dark:border-blue-900/50">
            <div class="text-sm text-blue-700 dark:text-blue-400">Under Review</div>
            <div class="text-2xl font-bold text-blue-800 dark:text-blue-300">{{ $stats['reviewing'] }}</div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow p-4 border border-green-200 dark:border-green-900/50">
            <div class="text-sm text-green-700 dark:text-green-400">Resolved</div>
            <div class="text-2xl font-bold text-green-800 dark:text-green-300">{{ $stats['resolved'] }}</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
            <div class="text-sm text-gray-700 dark:text-gray-300">Dismissed</div>
            <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $stats['dismissed'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4 border border-transparent dark:border-gray-700">
        <form method="GET" action="{{ route('admin.user-reports.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name, email, or reason..." class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white">
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Under Review</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                </select>
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Type</label>
                <select name="type" class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white">
                    <option value="">All Types</option>
                    @foreach($reportTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'type']))
                    <a href="{{ route('admin.user-reports.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-500 text-sm font-medium rounded-md text-gray-700 dark:text-gray-100 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-transparent dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Reported User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Reported By</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900 dark:text-white">#{{ $report->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($report->reported && $report->reported->profile_photo_path)
                                <img src="{{ route('profile.photo.serve', ['filename' => basename($report->reported->profile_photo_path)]) }}" 
                                     alt="Avatar" 
                                     class="h-10 w-10 rounded-xl object-cover border border-indigo-100 dark:border-indigo-800 shadow-sm">
                            @else
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-white text-sm font-black shadow-sm">
                                    {{ strtoupper(substr($report->reported->first_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-3">
                                <div class="text-sm font-black text-gray-900 dark:text-white">{{ $report->reported->fullName() ?? 'Unknown User' }}</div>
                                <div class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $report->reported->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center">
                            @if($report->reporter && $report->reporter->profile_photo_path)
                                <img src="{{ route('profile.photo.serve', ['filename' => basename($report->reporter->profile_photo_path)]) }}" 
                                     alt="Avatar" 
                                     class="h-8 w-8 rounded-lg object-cover border border-gray-200 dark:border-gray-700 mr-3 shadow-sm">
                            @else
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-gray-400 to-gray-600 border border-gray-300 dark:border-gray-600 flex items-center justify-center text-white text-[10px] font-black mr-3 shadow-sm">
                                    {{ strtoupper(substr($report->reporter->first_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-black text-gray-900 dark:text-white">{{ $report->reporter->fullName() ?? 'Unknown' }}</div>
                                <div class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $report->reporter->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full bg-slate-800 text-white dark:bg-slate-700 border border-slate-600">
                            {{ $report->getReportTypeLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/40 dark:text-amber-400 dark:border-amber-800',
                                'reviewing' => 'bg-indigo-100 text-indigo-800 border-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-400 dark:border-indigo-800',
                                'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400 dark:border-emerald-800',
                                'dismissed' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-black rounded-full {{ $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                            {{ $report->getStatusLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-800 dark:text-gray-300">
                        <div>{{ $report->created_at->format('M j, Y') }}</div>
                        <div class="text-[10px] text-gray-600 dark:text-gray-500">{{ $report->created_at->format('g:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.user-reports.show', $report) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-clipboard-list text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No reports found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reports->appends(request()->query())->links() }}
    </div>
</div>
@endsection
