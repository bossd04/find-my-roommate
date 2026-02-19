<div x-show="sidebarOpen" class="md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-indigo-700">
        <div class="flex flex-col h-0 flex-1">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <span class="text-xl font-semibold text-white">Admin Panel</span>
                </div>
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-indigo-300"></i>
                        Dashboard
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
                        <i class="fas fa-users mr-3 text-indigo-300"></i>
                        Users
                    </a>

                   <!-- Listings -->
<a href="{{ route('admin.listings.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.listings.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-home mr-3 text-indigo-300"></i>
    Listings
</a>

<!-- Messages -->
<a href="{{ route('admin.messages.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.messages.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-envelope mr-3 text-indigo-300"></i>
    Messages
</a>

<!-- Payments -->
<a href="{{ route('admin.payments.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-credit-card mr-3 text-indigo-300"></i>
    Payments
</a>

<!-- Reports -->
<a href="{{ route('admin.reports.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-chart-bar mr-3 text-indigo-300"></i>
    Reports
</a>

<!-- Settings -->
<a href="{{ route('admin.settings') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-cog mr-3 text-indigo-300"></i>
    Settings
</a>

<!-- Activity Logs -->
<a href="{{ route('admin.activity_logs.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.activity_logs.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-history mr-3 text-indigo-300"></i>
    Activity Logs
</a>

<!-- Backup -->
<a href="{{ route('admin.backup.index') }}" 
   class="group flex items-center px-2 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.backup.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:bg-opacity-75' }}">
    <i class="fas fa-database mr-3 text-indigo-300"></i>
    Backup
</a>

<!-- Reports Dropdown -->
<div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
        <div class="flex items-center">
            <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
            <span>Reports</span>
        </div>
        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    
    <!-- Dropdown menu -->
    <div x-show="open" class="mt-1 pl-4 space-y-1">
        <a href="{{ route('admin.reports.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-file-export mr-3 text-gray-400 group-hover:text-gray-500"></i>
            Generate Reports
        </a>
        
        <a href="{{ route('admin.reports.generate', ['type' => 'transactions']) }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.generate') && request('type') === 'transactions' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-exchange-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
            Transactions
        </a>
    </div>
</div>

<!-- System Settings -->
<a href="{{ route('admin.settings') }}" 
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
    System Settings
</a>
                    
                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <!-- Back to Site -->
                    <a href="{{ url('/') }}" 
                       class="group flex items-center px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Back to Site
                    </a>
                </nav>
            </div>
            
            <!-- Admin Profile -->
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <div class="flex items-center w-full">
                    <div class="relative">
                        @if(auth()->user()->profile_photo_path)
                            <img class="h-10 w-10 rounded-full object-cover sidebar-avatar" 
                                 src="{{ auth()->user()->profile_photo_url }}" 
                                 alt="{{ auth()->user()->name }}">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->name }}</p>
                        <a href="{{ route('admin.profile.show') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                            View profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
