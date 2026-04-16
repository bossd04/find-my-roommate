@extends('admin.layouts.app')

@section('title', 'Pending User Approvals')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Pending User Approvals</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    Users awaiting admin approval 
                    <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $users->total() }} pending)</span>
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-500/20 shadow-lg transition-all active:scale-95">
                <i class="fas fa-arrow-left mr-3"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">User</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Email</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Registered</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Activity</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($users as $user)
                            <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors" id="user-row-{{ $user->id }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if(!empty($user->avatar))
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm" 
                                                     src="{{ route('avatar.serve', ['filename' => $user->avatar]) }}" 
                                                     alt="{{ $user->name }}"
                                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNiA3YTQgNCAwIDExLTggMCA0IDQgMCAwMTggMFpNMTIgMTRhNyA3IDAgMDAtNyA3aDE0YTcgNyAwIDAwLTctN1oiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'">
                                            @elseif(!empty($user->profile_photo_path))
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm" 
                                                     src="{{ route('profile.photo.serve', ['filename' => basename($user->profile_photo_path)]) }}" 
                                                     alt="{{ $user->name }}"
                                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNiA3YTQgNCAwIDExLTggMCA0IDQgMCAwMTggMFpNMTIgMTRhNyA3IDAgMDAtNyA3aDE0YTcgNyAwIDAwLTctN1oiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-900/40 flex items-center justify-center text-gray-400 font-black text-xs">
                                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-black text-gray-900 dark:text-gray-50 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</div>
                                            <div class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter leading-none">{{ $user->is_admin ? 'Admin' : 'User' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-black text-gray-900 dark:text-gray-50 leading-tight">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-[11px] font-medium text-gray-400 dark:text-gray-500">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-black text-gray-500 dark:text-gray-300 leading-none">
                                        {{ $user->created_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-tighter">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-6">
                                        <span class="inline-flex items-center text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest" title="Listings">
                                            <i class="fas fa-home mr-2 text-indigo-400 dark:text-indigo-400"></i>
                                            {{ $user->listings_count }}
                                        </span>
                                        <span class="inline-flex items-center text-[10px] font-black text-gray-400 dark:text-gray-400 uppercase tracking-widest" title="Messages">
                                            <i class="fas fa-envelope mr-2 text-indigo-400 dark:text-indigo-400"></i>
                                            {{ $user->sent_messages_count + $user->received_messages_count }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="approveUser({{ $user->id }})" 
                                                class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-check mr-2"></i> Approve
                                        </button>
                                        <button onclick="rejectUser({{ $user->id }})" 
                                                class="px-4 py-2 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-md shadow-red-500/20 active:scale-95 transition-all">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="p-2 text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Premium Pagination -->
            @if($users->hasPages())
                <div class="bg-gray-50/50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-100 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="p-20 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-emerald-50 dark:bg-emerald-900/20 mb-6 group">
                    <i class="fas fa-user-check text-4xl text-emerald-500 group-hover:scale-125 transition-transform duration-500"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">All Users Verified</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-8">No accounts currently awaiting approval</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all active:scale-95">
                    <i class="fas fa-users mr-3"></i> View All Users
                </a>
            </div>
        @endif
    </div>
</div>
</div>

<script>
function approveUser(userId) {
    if (confirm('Are you sure you want to approve this user account?')) {
        const url = '{{ route("admin.users.approve", ":id") }}'.replace(':id', userId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('user-row-' + userId);
                if (row) {
                    row.classList.add('bg-green-50', 'transition-all', 'duration-500', 'opacity-0');
                    setTimeout(() => {
                        row.remove();
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload();
                        }
                    }, 500);
                }
            } else {
                alert(data.message || 'Error occurred');
            }
        });
    }
}

function rejectUser(userId) {
    if (confirm('Are you sure you want to reject and delete this user?')) {
        const url = '{{ route("admin.users.destroy", ":id") }}'.replace(':id', userId);
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                const row = document.getElementById('user-row-' + userId);
                if (row) {
                    row.classList.add('bg-red-50', 'transition-all', 'duration-500', 'opacity-0');
                    setTimeout(() => {
                        row.remove();
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload();
                        }
                    }, 500);
                }
            } else {
                alert('Error occurred');
            }
        });
    }
}
</script>
@endsection
