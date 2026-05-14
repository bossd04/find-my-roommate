@extends('admin.layouts.app')

@section('title', 'Users Management')

@push('styles')
<style>
    /* Responsive Contrast System */
    .high-contrast-title { color: #020617 !important; }
    .high-contrast-subtext { color: #374151 !important; }
    
    .dark .high-contrast-title { 
        color: #ffffff !important; 
        text-shadow: 0 0 30px rgba(255,255,255,0.1);
    }
    .dark .high-contrast-subtext { 
        color: #e2e8f0 !important; 
    }
    
    .dark .text-gray-500 { color: #9ca3af !important; }
    .dark .text-gray-400 { color: #818cf8 !important; }
    .dark .text-gray-600 { color: #cbd5e1 !important; }
    .dark .text-gray-700 { color: #e2e8f0 !important; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section with Sketch Background -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Users Management</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    @if(request('role'))
                        Showing {{ request('role') === 'admin' ? 'Administrators' : 'Regular Users' }} 
                        <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $users->total() }} total)</span>
                    @else
                        All Users <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $users->total() }} total)</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                <i class="fas fa-plus mr-3"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Filters and Search Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Role Filter Wrapper -->
                <div class="flex items-center space-x-4">
                    <span class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Filter by Role:</span>
                    <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl">
                        <button type="button" 
                                class="role-filter-btn px-6 py-2.5 text-xs font-black rounded-xl transition-all duration-300 {{ !request('role') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}" 
                                data-role="">
                            <i class="fas fa-users mr-2"></i> All Users
                        </button>
                        <button type="button" 
                                class="role-filter-btn px-6 py-2.5 text-xs font-black rounded-xl transition-all duration-300 {{ request('role') === 'admin' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}" 
                                data-role="admin">
                            <i class="fas fa-user-shield mr-2"></i> Admins
                        </button>
                        <button type="button" 
                                class="role-filter-btn px-6 py-2.5 text-xs font-black rounded-xl transition-all duration-300 {{ request('role') === 'user' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}" 
                                data-role="user">
                            <i class="fas fa-user mr-2"></i> Users
                        </button>
                    </div>
                </div>

                <!-- Search and Action Wrapper -->
                <div class="flex flex-1 max-w-2xl items-center space-x-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" id="search" name="search" 
                               class="block w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500" 
                               placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                    <button type="button" id="filter-btn" 
                            class="px-8 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-md shadow-indigo-500/20 active:scale-95 transition-all">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.users.index') }}" 
                       class="p-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-2xl transition-colors">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Borderless Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">User</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Role</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Last Active</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                    @if(!empty($user->avatar))
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm" 
                                             src="{{ route('avatar.serve', ['filename' => basename($user->avatar)]) }}" 
                                             alt="{{ $user->name }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNiA3YTQgNCAwIDExLTggMCA0IDQgMCAwMTggMFpNMTIgMTRhNyA3IDAgMDAtNyA3aDE0YTcgNyAwIDAwLTctN1oiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'">
                                    @elseif(!empty($user->profile_photo_path))
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-sm" 
                                             src="{{ route('profile.photo.serve', ['filename' => basename($user->profile_photo_path)]) }}" 
                                             alt="{{ $user->name }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNiA3YTQgNCAwIDExLTggMCA0IDQgMCAwMTggMFpNMTIgMTRhNyA3IDAgMDAtNyA3aDE0YTcgNyAwIDAwLTctN1oiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+'">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-black text-gray-900 dark:text-white leading-tight group-hover:text-indigo-600 transition-colors">{{ $user->name }}</div>
                                        <div class="text-[11px] font-medium text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if(isset($user->deleted_at) && $user->deleted_at)
                                    <span class="flex items-center text-xs font-black text-red-500 uppercase tracking-widest">
                                        <i class="fas fa-circle text-[8px] mr-2"></i> Deleted
                                    </span>
                                @elseif(!$user->is_active)
                                    <span class="flex items-center text-xs font-black text-orange-500 uppercase tracking-widest" title="Account deactivated">
                                        <i class="fas fa-ban text-[8px] mr-2"></i> Inactive
                                    </span>
                                @elseif(isset($user->last_seen_at) && $user->last_seen_at && \Carbon\Carbon::parse($user->last_seen_at)->diffInMinutes() < 5)
                                    <span class="flex items-center text-xs font-black text-emerald-500 uppercase tracking-widest">
                                        <i class="fas fa-circle text-[8px] mr-2 animate-pulse"></i> Online
                                    </span>
                                @else
                                    <span class="flex items-center text-xs font-black text-gray-400 uppercase tracking-widest">
                                        <i class="fas fa-circle text-[8px] mr-2"></i> Offline
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm font-bold text-gray-500">
                                {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="p-2 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                          onsubmit="return deleteUser(event, {{ $user->id }})">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                                title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users-slash text-4xl text-gray-200 mb-4"></i>
                                    <p class="text-sm font-bold text-gray-400">No users found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Premium Pagination -->
        @if($users->hasPages())
            <div class="bg-gray-50/50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-100 dark:border-gray-700">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const filterBtn = document.getElementById('filter-btn');
        const roleFilterButtons = document.querySelectorAll('.role-filter-btn');
        let currentRole = '{{ request('role') }}';
        
        // Handle role filter button clicks
        roleFilterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const selectedRole = this.getAttribute('data-role');
                
                // Update button styles
                roleFilterButtons.forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                });
                
                this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                this.classList.add('bg-indigo-600', 'text-white');
                
                // Update current role and apply filter immediately
                currentRole = selectedRole;
                applyFilters();
            });
        });
        
        // Apply filters function
        function applyFilters() {
            const search = searchInput.value.trim();
            
            let url = new URL(window.location.href.split('?')[0]);
            let params = new URLSearchParams();
            
            if (search) params.append('search', search);
            if (currentRole) params.append('role', currentRole);
            
            window.location.href = url.toString() + (params.toString() ? '?' + params.toString() : '');
        }
        
        // Handle filter button click
        filterBtn.addEventListener('click', function() {
            applyFilters();
        });
        
        // Handle Enter key in search input
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    });
</script>
@endpush

<script>
function deleteUser(event, userId) {
    event.preventDefault();
    
    if (!confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')) {
        return false;
    }
    
    const form = event.target;
    const formData = new FormData(form);
    const row = document.getElementById('user-row-' + userId);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the row with animation
            if (row) {
                row.style.transition = 'opacity 0.3s, transform 0.3s';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    row.remove();
                    
                    // Check if table is empty and reload if needed
                    const tbody = document.querySelector('tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            // Show success message
            showNotification(data.message || 'User deleted successfully', 'success');
        } else {
            // Show error message
            showNotification(data.message || 'Error deleting user', 'error');
            
            // Restore button
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deleting user', 'error');
        
        // Restore button
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
    });
    
    return false;
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 flex items-center space-x-2 transition-all transform translate-x-full`;
    
    // Set colors based on type
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }
    
    // Add icon
    const icon = type === 'success' 
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    
    notification.innerHTML = `
        ${icon}
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ── Read flash message from sessionStorage after redirect ────────────────
document.addEventListener('DOMContentLoaded', function () {
    const flashMsg = sessionStorage.getItem('admin_flash_success');
    if (flashMsg) {
        sessionStorage.removeItem('admin_flash_success');
        showNotification(flashMsg, 'success');
    }
});
</script>

@endsection
