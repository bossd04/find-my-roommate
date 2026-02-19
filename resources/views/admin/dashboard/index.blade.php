<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="mt-2 text-sm text-gray-600">Welcome back! Here's what's happening with your application.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-500">Total Users</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $users->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admins Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-500">Administrators</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $adminCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Users Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-500">Regular Users</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $userCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">User Management</h3>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i> Add New User
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-indigo-600 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-indigo-600 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-indigo-600 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-indigo-600 uppercase tracking-wider">Last Login</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-indigo-600 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-indigo-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-800">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->role === 'admin')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Admin
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                        @else
                                            Never logged in
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->last_login_at && $user->last_login_at->gt(now()->subMinutes(5)))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Online
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Offline
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Active Listings -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-home text-green-500 text-3xl"></i>
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Active Listings
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $listingCount ?? 0 }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-green-600 hover:text-green-500">
                                View all
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Pending Approvals
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $pendingApprovals ?? 0 }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500">
                                Review
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Messages -->
                <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-purple-500 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Messages
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $messageCount ?? 0 }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-purple-600 hover:text-purple-500">
                                View all
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Recent Activity
                        </h3>
                    </div>
                    <div class="bg-white shadow overflow-hidden sm:rounded-b-lg">
                        <ul class="divide-y divide-gray-200">
                            <!-- Example activity items -->
                            <li class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-plus text-green-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">New user registered</p>
                                        <p class="text-sm text-gray-500">2 minutes ago</p>
                                    </div>
                                </div>
                            </li>
                            <li class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-home text-blue-500"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">New listing added</p>
                                        <p class="text-sm text-gray-500">1 hour ago</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
