<div x-data="{ open: false }">
    <!-- Mobile menu button -->
    <div class="md:hidden bg-indigo-900 p-4 flex justify-between items-center">
        <div class="text-white font-bold text-xl">Admin Panel</div>
        <button @click="open = !open" class="text-white focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-indigo-900 h-screen fixed">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-indigo-900">
                <span class="text-white text-xl font-bold">Admin Panel</span>
            </div>
            
            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto">
                <nav class="px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/dashboard*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-indigo-300"></i>
                        Dashboard
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/users*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-users mr-3 text-indigo-300"></i>
                        Users
                    </a>

                    <!-- Listings -->
                    <a href="{{ route('admin.listings.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/listings*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-home mr-3 text-indigo-300"></i>
                        Listings
                    </a>

                    <!-- Messages -->
                    <a href="{{ route('admin.messages.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/messages*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-envelope mr-3 text-indigo-300"></i>
                        Messages
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">3</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('admin.reports.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/reports*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-flag mr-3 text-indigo-300"></i>
                        Reports
                        <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">5</span>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/settings*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-cog mr-3 text-indigo-300"></i>
                        Settings
                    </a>

                    <!-- Activity Logs -->
                    <a href="{{ route('admin.activity_logs.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/activity-logs*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-history mr-3 text-indigo-300"></i>
                        Activity Logs
                    </a>

                    <!-- Backup -->
                    <a href="{{ route('admin.backup.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->is('admin/backup*') ? 'bg-indigo-800' : '' }}">
                        <i class="fas fa-database mr-3 text-indigo-300"></i>
                        Backup
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left text-white hover:bg-indigo-800 group flex items-center px-4 py-3 text-sm font-medium rounded-md">
                            <i class="fas fa-sign-out-alt mr-3 text-indigo-300"></i>
                            Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" @click.away="open = false" class="md:hidden fixed inset-0 z-40">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="open = false"></div>
        <div class="fixed inset-y-0 left-0 flex w-5/6 max-w-sm z-50">
            <div class="relative flex-1 flex flex-col w-full bg-indigo-900">
                <div class="absolute top-0 right-0 -mr-14 p-1">
                    <button @click="open = false" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600">
                        <i class="fas fa-times text-white text-xl"></i>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <span class="text-white text-xl font-bold">Admin Panel</span>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        <!-- Mobile Navigation Links (same as desktop) -->
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3 text-indigo-300"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-users mr-3 text-indigo-300"></i>
                            Users
                        </a>
                        <a href="{{ route('admin.listings.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-home mr-3 text-indigo-300"></i>
                            Listings
                        </a>
                        <a href="{{ route('admin.backup.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-database mr-3 text-indigo-300"></i>
                            Backup
                        </a>
                        <a href="{{ route('admin.messages.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-envelope mr-3 text-indigo-300"></i>
                            Messages
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">3</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-flag mr-3 text-indigo-300"></i>
                            Reports
                            <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">5</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-cog mr-3 text-indigo-300"></i>
                            Settings
                        </a>
                        <a href="{{ route('admin.activity_logs.index') }}" class="text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-history mr-3 text-indigo-300"></i>
                            Activity Logs
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-indigo-800 p-4">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left text-white hover:bg-indigo-800 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                            <i class="fas fa-sign-out-alt mr-3 text-indigo-300"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</nav>
