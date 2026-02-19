@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Users Management</h1>
            @if(request('role'))
                <p class="mt-1 text-sm text-gray-600">
                    Showing {{ request('role') === 'admin' ? 'Administrators' : 'Regular Users' }} 
                    <span class="font-medium">({{ $users->total() }} found)</span>
                </p>
            @else
                <p class="mt-1 text-sm text-gray-600">
                    All Users <span class="font-medium">({{ $users->total() }} total)</span>
                </p>
            @endif
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Add New User
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4 border-b border-gray-200">
            <!-- Role Filter Buttons -->
            <div class="mb-4">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Filter by Role:</span>
                    <div class="flex space-x-1" id="role-filter-buttons">
                        <button type="button" 
                                class="role-filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ !request('role') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}" 
                                data-role="">
                            <i class="fas fa-users mr-2"></i>All Users
                        </button>
                        <button type="button" 
                                class="role-filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request('role') === 'admin' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}" 
                                data-role="admin">
                            <i class="fas fa-user-shield mr-2"></i>Admins
                        </button>
                        <button type="button" 
                                class="role-filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request('role') === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}" 
                                data-role="user">
                            <i class="fas fa-user mr-2"></i>Users
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button type="button" id="filter-btn" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Active
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->trashed())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Deleted
                                    </span>
                                @elseif($user->isOnline())
                                    <span class="flex items-center">
                                        <span class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></span>
                                        <span class="text-sm text-gray-600">Online</span>
                                    </span>
                                @else
                                    <span class="flex items-center">
                                        <span class="h-2.5 w-2.5 rounded-full bg-gray-300 mr-2"></span>
                                        <span class="text-sm text-gray-600">Offline</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->is_admin ? 'Administrator' : 'User' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->trashed())
                                        <form action="{{ route('admin.users.restore', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900" 
                                                    title="Restore"
                                                    onclick="return confirm('Are you sure you want to restore this user?')">
                                                <i class="fas fa-trash-restore"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.force-delete', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    title="Permanently Delete"
                                                    onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $users->firstItem() }}</span> to 
                            <span class="font-medium">{{ $users->lastItem() }}</span> of 
                            <span class="font-medium">{{ $users->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
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

@endsection
