@extends('admin.layouts.app')

@section('title', 'Report #' . $report->id)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black tracking-tight" style="color: #020617 !important;">Report #{{ $report->id }}</h1>
            <p class="mt-1 text-sm font-bold" style="color: #374151 !important;">{{ $report->created_at->format('M j, Y g:i A') }}</p>
        </div>
        <a href="{{ route('admin.user-reports.index') }}" class="inline-flex items-center px-4 py-2 border border-indigo-200 dark:border-indigo-900 text-sm font-bold rounded-xl text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-all duration-300 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to Reports List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-transparent dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $report->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-400 border-yellow-200' : ($report->status === 'resolved' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 border-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-200') }}">
                            {{ $report->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        @if($report->isPending())
                            <form action="{{ route('admin.user-reports.reviewing', $report) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-bold transition-colors">Start Review</button>
                            </form>
                        @endif
                        @if(in_array($report->status, ['pending', 'reviewing']))
                            <button onclick="document.getElementById('resolve-modal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-bold transition-colors">Resolve</button>
                            <button onclick="document.getElementById('dismiss-modal').classList.remove('hidden')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-bold transition-colors">Dismiss</button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-transparent dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Report Details</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Report Type</label>
                        <p class="text-gray-900 dark:text-white font-medium bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700 inline-block">
                            {{ $report->getReportTypeLabel() }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Detailed Reason</label>
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 text-gray-900 dark:text-white leading-relaxed">
                            {{ $report->reason }}
                        </div>
                    </div>
                    @if($report->admin_notes || $report->status !== 'pending')
                    <div>
                        <label class="block text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">Admin Resolution Notes</label>
                        <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800 text-indigo-900 dark:text-white leading-relaxed font-medium">
                            @if($report->admin_notes)
                                {{ $report->admin_notes }}
                            @else
                                <span class="italic text-indigo-400 dark:text-indigo-500">No notes provided during resolution.</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Reported User -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-transparent dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Reported User</h3>
                <div class="flex items-center mb-6">
                @if($report->reported && $report->reported->profile_photo_path)
                    <img src="{{ route('profile.photo.serve', ['filename' => basename($report->reported->profile_photo_path)]) }}" 
                         alt="Avatar" 
                         class="h-16 w-16 rounded-2xl object-cover border-2 border-indigo-100 dark:border-indigo-900 shadow-lg">
                @else
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 border-2 border-indigo-200 dark:border-indigo-800 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-indigo-900/20">
                        {{ strtoupper(substr($report->reported->first_name ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <div class="ml-4">
                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $report->reported->fullName() ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reported->email ?? '' }}</p>
                </div>
            </div>
                <a href="{{ route('admin.users.show', $report->reported_id) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">
                    View Full Profile <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                </a>
            </div>

            <!-- Reporter -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-transparent dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Reported By</h3>
                <div class="flex items-center">
                @if($report->reporter && $report->reporter->profile_photo_path)
                    <img src="{{ route('profile.photo.serve', ['filename' => basename($report->reporter->profile_photo_path)]) }}" 
                         alt="Avatar" 
                         class="h-12 w-12 rounded-xl object-cover border-2 border-gray-100 dark:border-gray-700 shadow-md">
                @else
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-400 to-gray-600 border-2 border-gray-200 dark:border-gray-600 flex items-center justify-center text-white text-lg font-black shadow-md">
                        {{ strtoupper(substr($report->reporter->first_name ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <div class="ml-4">
                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $report->reporter->fullName() ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->reporter->email ?? '' }}</p>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolve-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" ariale-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="document.getElementById('resolve-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl p-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-transparent dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Resolve Report</h3>
            <form action="{{ route('admin.user-reports.resolve', $report) }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resolution Notes</label>
                    <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl p-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describe the action taken..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('resolve-modal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-900/20 transition-colors">Confirm Resolve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dismiss Modal -->
<div id="dismiss-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity" aria-hidden="true" onclick="document.getElementById('dismiss-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl p-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-transparent dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Dismiss Report</h3>
            <form action="{{ route('admin.user-reports.dismiss', $report) }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dismissal Reason</label>
                    <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl p-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Why is this report being dismissed?"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('dismiss-modal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-bold transition-colors">Confirm Dismiss</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
