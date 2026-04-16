<nav x-data="{ open: false }" class="bg-indigo-900 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold flex items-center space-x-2">
                        <!-- Logo SVG -->
                        <svg class="w-8 h-8" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <!-- House outline -->
                            <path d="M20 45 L50 20 L80 45 L80 80 L20 80 Z" fill="none" stroke="#3B82F6" stroke-width="3"/>
                            <!-- Door -->
                            <rect x="42" y="55" width="16" height="25" fill="#3B82F6"/>
                            <!-- Windows -->
                            <rect x="28" y="35" width="12" height="12" fill="#3B82F6"/>
                            <rect x="60" y="35" width="12" height="12" fill="#3B82F6"/>
                            <!-- First person (blue) -->
                            <circle cx="35" cy="65" r="4" fill="#3B82F6"/>
                            <path d="M35 69 L35 75 M30 72 L40 72" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                            <!-- Second person (orange) -->
                            <circle cx="65" cy="65" r="4" fill="#FB923C"/>
                            <path d="M65 69 L65 75 M60 72 L70 72" stroke="#FB923C" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Find My Roommate</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:ms-10 sm:flex items-center">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-indigo-100 hover:bg-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('admin*') ? 'bg-indigo-800' : '' }}">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1h3a1 1 0 011 1v3m0 0h3m-3 0v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3z"></path>
                                </svg> {{ __('Admin Dashboard') }}
                            </a>
                        @else
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-indigo-100 hover:bg-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2l6 6"></path>
                                </svg> {{ __('Dashboard') }}
                            </x-nav-link>
                            <a href="{{ route('chatbot.index') }}" class="text-indigo-100 hover:bg-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('chatbot*') ? 'bg-indigo-800' : '' }}">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg> {{ __('AI Assistant') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown - Only for authenticated users -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Dark Mode Toggle -->
                <div class="flex items-center space-x-2">
                    <button id="nav-theme-toggle" class="p-2 text-indigo-100 hover:text-white rounded-md transition-colors" title="Toggle Dark Mode">
                        <svg id="nav-theme-icon-light" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="nav-theme-icon-dark" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9 9 0 0012.707 8.293 9 9 0 0015.354 15.354zM9 12a3 3 0 106 0 3 3 0 00-6 0z" />
                        </svg>
                    </button>
                </div>
                
                <!-- Notifications Button -->
                <div class="relative me-4">
                    <button onclick="toggleNotifications()" id="notificationButton" class="relative p-2 text-white hover:text-indigo-200 focus:outline-none transition-all duration-200 transform hover:scale-110">
                        <svg id="notificationIcon" class="w-6 h-6 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="hidden bg-white rounded-lg shadow-2xl border border-gray-200" 
                         style="z-index: 999999 !important; position: fixed !important; min-width: 20rem !important; max-width: 24rem !important; background: white !important;">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @php
                                $newUsers = \App\Models\User::where('created_at', '>', now()->subDays(7))->count();
                                $newMessages = \App\Models\Message::where('created_at', '>', now()->subHours(24))->where('receiver_id', auth()->id())->count();
                                $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @if($notifications->count() > 0)
                                @foreach($notifications as $notification)
                                    <div id="notification-card-{{ $notification->id }}" class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-all duration-200" onclick="markAsRead({{ $notification->id }})">
                                        <div class="flex items-start">
                                            <div class="w-8 h-8 {{ $notification->type === 'new_listing' ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3 transition-colors duration-200">
                                                <svg class="w-4 h-4 {{ $notification->type === 'new_listing' ? 'text-blue-600' : 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if($notification->type === 'new_listing')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.919 1.972 1.972 0 00.12-.7A2.032 2.032 0 0112 6c.634 0 1.237.298 1.65.768.413.47.696.856.855 1.405l1.405 1.405z" />
                                                    @endif
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 bg-blue-500 rounded-full transition-colors duration-200"></span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-4 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.919 1.972 1.972 0 00.12-.7A2.032 2.032 0 0112 6c.634 0 1.237.298 1.65.768.413.47.696.856.855 1.405l1.405 1.405z" />
                                    </svg>
                                    <p class="text-sm font-medium text-indigo-600">No notifications yet</p>
                                </div>
                            @endif
                            
                            <div class="p-4 text-center border-t border-indigo-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-all duration-200">View all notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-white hover:text-indigo-200 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full overflow-hidden bg-indigo-700 border-2 border-white shadow-lg hover:shadow-xl transition-shadow duration-200">
                                    @if(Auth::user()->avatar_url)
                                        <img src="{{ Auth::user()->avatar_url }}?{{ time() }}" 
                                             alt="{{ Auth::user()->fullName() }}" 
                                             class="h-full w-full object-cover global-avatar-update hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold hover:from-indigo-600 hover:to-purple-700 transition-all duration-200">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-white">{{ Auth::user()->first_name ?? 'User' }}</span>
                                    <span class="text-xs text-indigo-200">View Profile</span>
                                </div>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <!-- Guest: Show Login/Register links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('login') }}" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Sign Up
                </a>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('admin*') ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1h3a1 1 0 011 1v3m0 0h3m-3 0v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3z"></path>
                    </svg> {{ __('Admin Dashboard') }}
                </a>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2l6 6"></path>
                    </svg> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <a href="{{ route('chatbot.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('chatbot*') ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg> {{ __('AI Assistant') }}
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full overflow-hidden bg-indigo-700 border-2 border-white shadow-lg">
                        @if(Auth::user()->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}?{{ time() }}" 
                                 alt="{{ Auth::user()->fullName() }}" 
                                 class="h-full w-full object-cover global-avatar-update">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-base text-gray-900">{{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}</div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                        <div class="text-xs text-indigo-600 mt-1">View Profile →</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <!-- Guest mobile menu -->
        <div class="pt-2 pb-3 space-y-1 border-b border-gray-200">
            <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 text-base font-medium">
                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3a4 4 0 00-4 4H9a4 4 0 00-4 4v1a1 1 0 001 1h6a1 1 0 001-1V7a4 4 0 004-4h2a4 4 0 004 4v8a2 2 0 01-2 2H7a2 2 0 01-2-2V7a4 4 0 00-4-4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l1.5 1.5"></path>
                </svg> Log In
            </a>
            <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 text-base font-medium">
                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0 0h3m-3 0h-3m2-4h3m-3 0h3m-6-4v12m0 0l3-3m-3 3l-3-3"></path>
                </svg> Sign Up
            </a>
        </div>
        @endauth
    </div>
</nav>

<script>
// Navigation Theme Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navThemeToggle = document.getElementById('nav-theme-toggle');
    const navThemeIconLight = document.getElementById('nav-theme-icon-light');
    const navThemeIconDark = document.getElementById('nav-theme-icon-dark');
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        if (navThemeIconLight) navThemeIconLight.classList.add('hidden');
        if (navThemeIconDark) navThemeIconDark.classList.remove('hidden');
    }
    
    // Handle navigation theme toggle
    if (navThemeToggle) {
        navThemeToggle.addEventListener('click', function() {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            if (navThemeIconLight) navThemeIconLight.classList.toggle('hidden', isDark);
            if (navThemeIconDark) navThemeIconDark.classList.toggle('hidden', !isDark);
            
            // Also sync with sidebar toggle if it exists
            const sidebarToggle = document.getElementById('theme-toggle');
            if (sidebarToggle) {
                sidebarToggle.checked = isDark;
            }
        });
    }
});

function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    const button = document.getElementById('notificationButton');
    const icon = document.getElementById('notificationIcon');
    
    // Exit if elements don't exist
    if (!dropdown || !button || !icon) return;
    
    // Always show the dropdown when clicked
    dropdown.classList.remove('hidden');
    
    // Add bell ring animation
    button.classList.add('clicked');
    setTimeout(() => {
        button.classList.remove('clicked');
    }, 500);
    
    // Change button color when clicked
    button.classList.add('text-yellow-400');
    button.classList.remove('text-white');
    icon.style.color = '#fbbf24'; // yellow-400
    
    // Position the dropdown correctly below the button
    const buttonRect = button.getBoundingClientRect();
    
    // Calculate position
    const dropdownTop = buttonRect.bottom + window.scrollY + 8;
    const dropdownRight = window.innerWidth - buttonRect.right;
    
    // Set position with !important override
    dropdown.style.setProperty('top', dropdownTop + 'px', 'important');
    dropdown.style.setProperty('right', dropdownRight + 'px', 'important');
    dropdown.style.setProperty('left', 'auto', 'important');
    dropdown.style.setProperty('bottom', 'auto', 'important');
    
    // Force reflow to ensure positioning takes effect
    dropdown.offsetHeight;
    
    // Add a small delay to ensure it renders above everything
    setTimeout(() => {
        dropdown.style.setProperty('z-index', '999999', 'important');
    }, 10);
    
    // Reset color after 2 seconds or when dropdown is closed
    setTimeout(() => {
        if (dropdown.classList.contains('hidden')) {
            resetNotificationColor();
        }
    }, 2000);
}

// Function to reset notification button color
function resetNotificationColor() {
    const button = document.getElementById('notificationButton');
    const icon = document.getElementById('notificationIcon');
    
    // Exit if elements don't exist
    if (!button || !icon) return;
    
    button.classList.remove('text-yellow-400');
    button.classList.add('text-white');
    icon.style.color = '';
}

// Mark notification as read
function markAsRead(notificationId) {
    // Add visual feedback - change card color immediately
    const notificationCard = document.getElementById(`notification-card-${notificationId}`);
    if (notificationCard) {
        // Change background color to indicate it's been clicked
        notificationCard.style.backgroundColor = '#fef3c7'; // amber-50
        notificationCard.style.borderColor = '#f59e0b'; // amber-500
        notificationCard.style.transition = 'all 0.3s ease';
        
        // Change icon background color
        const iconContainer = notificationCard.querySelector('.w-8.h-8');
        if (iconContainer) {
            iconContainer.style.backgroundColor = '#f59e0b'; // amber-500
            const icon = iconContainer.querySelector('svg');
            if (icon) {
                icon.style.color = 'white';
            }
        }
        
        // Change unread indicator
        const unreadIndicator = notificationCard.querySelector('.bg-blue-500');
        if (unreadIndicator) {
            unreadIndicator.style.backgroundColor = '#10b981'; // emerald-500
        }
    }
    
    fetch(`{{ route('notifications.read', ['notification' => ':id']) }}`.replace(':id', notificationId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Keep the color change for a moment, then reload
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        // Reset color on error
        if (notificationCard) {
            notificationCard.style.backgroundColor = '';
            notificationCard.style.borderColor = '';
        }
    });
}

// Close notifications when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationsDropdown');
    
    // Exit if dropdown doesn't exist on this page
    if (!dropdown) return;
    
    const button = event.target.closest('button[onclick="toggleNotifications()"]');
    
    // Only hide if clicking outside the dropdown and not on the button
    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
        // Reset notification button color when dropdown is closed
        resetNotificationColor();
    }
});
</script>
