@extends('admin.layouts.app')

@section('title', 'Account Deletion Requests')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .request-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .request-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-cancelled {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .dark .status-pending {
            background-color: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        .dark .status-approved {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }
        .dark .status-rejected {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }
        .dark .status-cancelled {
            background-color: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
        }
        .tab-btn {
            position: relative;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }
        .tab-btn.active {
            color: #7c3aed;
            border-bottom-color: #7c3aed;
        }
        .tab-btn:hover:not(.active) {
            color: #6b7280;
            border-bottom-color: #e5e7eb;
        }
        .dark .tab-btn.active {
            color: #a78bfa;
            border-bottom-color: #a78bfa;
        }
        .high-contrast-title { color: #020617 !important; }
        .high-contrast-subtext { color: #374151 !important; }
        .dark .high-contrast-title { color: #ffffff !important; }
        .dark .high-contrast-subtext { color: #e2e8f0 !important; }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight high-contrast-title">
                <i class="fas fa-user-times mr-3 text-red-600"></i>Account Deletion Requests
            </h1>
            <p class="mt-2 text-base font-medium high-contrast-subtext">Review and manage user account deletion requests.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.superadmin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-t-2xl shadow-lg border border-gray-100 dark:border-gray-700 border-b-0">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button class="tab-btn active" onclick="switchTab('pending')">
                <i class="fas fa-clock mr-2 text-amber-500"></i>
                Pending
                <span class="ml-2 bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingRequests->total() }}</span>
            </button>
            <button class="tab-btn" onclick="switchTab('processed')">
                <i class="fas fa-check-double mr-2 text-gray-500"></i>
                Processed
                <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold px-2 py-0.5 rounded-full">{{ $processedRequests->total() }}</span>
            </button>
        </div>
    </div>

    <!-- Pending Requests Tab -->
    <div id="pending-tab" class="bg-white dark:bg-gray-800 rounded-b-2xl shadow-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                        <div class="request-card bg-white dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <!-- User Info -->
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 overflow-hidden flex items-center justify-center">
                                        @if($request->user && $request->user->profile_photo_url)
                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->fullName() }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-lg">{{ substr($request->user->first_name ?? 'U', 0, 1) }}{{ substr($request->user->last_name ?? 'N', 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">
                                            {{ $request->user ? $request->user->fullName() : 'Unknown User' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-envelope mr-1"></i>{{ $request->user ? $request->user->email : 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i>Requested {{ $request->requested_at->diffForHumans() }}
                                            <span class="mx-2 text-gray-300">|</span>
                                            <i class="fas fa-clock mr-1"></i>{{ $request->requested_at->format('M j, Y g:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Status & Actions -->
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-hourglass-half mr-1"></i>Pending
                                    </span>
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.superadmin.deletion-requests.approve', $request) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-bold transition-colors" onclick="return confirm('Are you sure you want to approve this deletion request? This will permanently delete the user account.')">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $request->id }}, '{{ $request->user->fullName() }}')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold transition-colors">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Reason -->
                            @if($request->reason)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">Reason for leaving:</span><br>
                                        {{ $request->reason }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pendingRequests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-4xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No Pending Requests</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">There are no pending account deletion requests at this time. All user accounts are safe.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Processed Requests Tab -->
    <div id="processed-tab" class="bg-white dark:bg-gray-800 rounded-b-2xl shadow-lg border border-gray-100 dark:border-gray-700 hidden">
        <div class="p-6">
            @if($processedRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($processedRequests as $request)
                        <div class="request-card bg-white dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <!-- User Info -->
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                                        @if($request->user && $request->user->profile_photo_url)
                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->fullName() }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400 font-bold text-lg">{{ substr($request->user->first_name ?? 'U', 0, 1) }}{{ substr($request->user->last_name ?? 'N', 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">
                                            {{ $request->user ? $request->user->fullName() : 'Unknown User' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-envelope mr-1"></i>{{ $request->user ? $request->user->email : 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i>Requested {{ $request->requested_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Status & Process Info -->
                                <div class="flex flex-col items-start sm:items-end gap-2">
                                    @if($request->isApproved())
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    @elseif($request->isRejected())
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times mr-1"></i>Rejected
                                        </span>
                                    @elseif($request->isCancelled())
                                        <span class="status-badge status-cancelled">
                                            <i class="fas fa-ban mr-1"></i>Cancelled
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        @if($request->processor)
                                            by {{ $request->processor->fullName() }}
                                        @else
                                            System
                                        @endif
                                    </p>
                                    @if($request->processed_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-clock mr-1"></i>{{ $request->processed_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Admin Notes -->
                            @if($request->admin_notes)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">Admin Notes:</span><br>
                                        {{ $request->admin_notes }}
                                    </p>
                                </div>
                            @endif

                            <!-- Original Reason -->
                            @if($request->reason)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">User's Reason:</span> {{ $request->reason }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $processedRequests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-4xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No Processed Requests</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">There are no processed account deletion requests yet. All requests are either pending or haven't been submitted.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md mx-4 shadow-2xl w-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Reject Deletion Request</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            You are about to reject the account deletion request for <span id="reject-user-name" class="font-semibold text-gray-900 dark:text-white"></span>.
        </p>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Reason for Rejection (Optional)
                </label>
                <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white resize-none" placeholder="Enter your reason for rejecting this request..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium">
                    <i class="fas fa-times mr-1"></i>Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const pendingTab = document.getElementById('pending-tab');
        const processedTab = document.getElementById('processed-tab');
        const tabBtns = document.querySelectorAll('.tab-btn');

        if (tab === 'pending') {
            pendingTab.classList.remove('hidden');
            processedTab.classList.add('hidden');
            tabBtns[0].classList.add('active');
            tabBtns[1].classList.remove('active');
        } else {
            pendingTab.classList.add('hidden');
            processedTab.classList.remove('hidden');
            tabBtns[0].classList.remove('active');
            tabBtns[1].classList.add('active');
        }
    }

    function openRejectModal(requestId, userName) {
        document.getElementById('reject-user-name').textContent = userName;
        document.getElementById('reject-form').action = `/admin/superadmin/deletion-requests/${requestId}/reject`;
        document.getElementById('reject-modal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
        document.getElementById('admin_notes').value = '';
    }

    // Close modal when clicking outside
    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>
@endpush
