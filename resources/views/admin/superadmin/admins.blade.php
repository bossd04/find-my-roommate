@extends('admin.layouts.app')

@section('title', 'Manage Admins')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                <i class="fas fa-user-shield mr-3 text-purple-600"></i>Manage Admins
            </h1>
            <p class="mt-2 text-base font-medium text-gray-500 dark:text-gray-400">View and manage administrator accounts.</p>
        </div>
        <a href="{{ route('admin.superadmin.create-admin') }}" 
           class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold uppercase tracking-wider rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus mr-2"></i>Create Admin
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
            <p class="text-emerald-700 dark:text-emerald-300 font-bold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl">
            <p class="text-rose-700 dark:text-rose-300 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Admins Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Joined</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($admin->profile_photo_path)
                                        <img src="{{ route('profile.photo.serve', ['filename' => basename($admin->profile_photo_path)]) }}" 
                                             class="h-10 w-10 rounded-xl object-cover border border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                            <span class="text-purple-600 dark:text-purple-400 font-bold">{{ strtoupper(substr($admin->first_name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $admin->fullName() }}</p>
                                        @if($admin->id === auth('admin')->id())
                                            <span class="text-xs text-purple-600 dark:text-purple-400 font-medium">(You)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $admin->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($admin->is_superadmin)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                        <i class="fas fa-crown mr-1"></i>Super Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                                        <i class="fas fa-user-shield mr-1"></i>Admin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($admin->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-2"></span>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $admin->created_at->format('M j, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($admin->id !== auth('admin')->id())
                                        @if($admin->is_admin && !$admin->is_superadmin)
                                            <form action="{{ route('admin.superadmin.toggle-superadmin', $admin) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors"
                                                        title="Promote to Super Admin">
                                                    <i class="fas fa-crown"></i>
                                                </button>
                                            </form>
                                        @elseif($admin->is_superadmin)
                                            <form action="{{ route('admin.superadmin.toggle-superadmin', $admin) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors"
                                                        title="Demote from Super Admin">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.superadmin.toggle-admin', $admin) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove admin privileges from this user?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"
                                                    title="Remove Admin Access">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.superadmin.destroy-admin', $admin) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this admin? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                                    title="Delete Admin">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Current User</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400">No admin users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($admins->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
