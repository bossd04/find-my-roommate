@extends('admin.layouts.app')

@section('title', 'Reported Users Summary')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight" style="color: #020617 !important;">Reported Users Summary</h1>
            <p class="mt-2 text-base font-medium" style="color: #374151 !important;">Users with the most reports across the system</p>
        </div>
        <div>
            <a href="{{ route('admin.user-reports.index') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg shadow-indigo-900/20">
                <i class="fas fa-list mr-2"></i> View All Reports
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-indigo-600 rounded-2xl shadow-lg p-5 border border-indigo-500 overflow-hidden relative group">
            <div class="relative z-10">
                <div class="text-indigo-100 text-xs font-black uppercase tracking-widest mb-1">Total Unique Reported Users</div>
                <div class="text-3xl font-black text-white leading-none">{{ $reportedUsers->total() }}</div>
            </div>
            <i class="fas fa-users absolute -right-4 -bottom-4 text-6xl text-indigo-500/30 group-hover:scale-110 transition-transform duration-500"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-xl border border-transparent dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Reports</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pending Reports</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reportedUsers as $stat)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($stat->reported && $stat->reported->profile_photo_path)
                                <img src="{{ route('profile.photo.serve', ['filename' => basename($stat->reported->profile_photo_path)]) }}" 
                                     alt="Avatar" 
                                     class="h-10 w-10 rounded-xl object-cover border border-indigo-100 dark:border-indigo-800 shadow-sm">
                            @else
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-white text-sm font-black shadow-sm">
                                    {{ strtoupper(substr($stat->reported->first_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-3">
                                <div class="text-sm font-black text-gray-900 dark:text-white">{{ $stat->reported->fullName() ?? 'Unknown User' }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-300">{{ $stat->reported->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black {{ $stat->report_count >= 5 ? 'bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-200 border border-red-200 dark:border-red-800' : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-200 border border-indigo-200 dark:border-indigo-800' }}">
                            <i class="fas fa-flag mr-1.5 text-[10px]"></i> {{ $stat->report_count }} Reports
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black {{ $stat->pending_count > 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/60 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800' : 'bg-green-100 text-green-700 dark:bg-green-900/60 dark:text-green-200 border border-green-200 dark:border-green-800' }}">
                            <i class="fas fa-clock mr-1.5 text-[10px]"></i> {{ $stat->pending_count }} Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($stat->reported->is_banned ?? false)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-900/50">
                                Banned
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-900/50">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.user-reports.index', ['search' => $stat->reported->email]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold">
                                View All Reports
                            </a>
                            <a href="{{ route('admin.users.show', $stat->reported_id) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-bold">
                                View Profile
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user-shield text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No reported users matching criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reportedUsers->links() }}
    </div>
</div>
@endsection
