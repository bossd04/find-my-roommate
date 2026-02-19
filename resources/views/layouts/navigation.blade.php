<nav x-data="{ open: false }" class="bg-indigo-900 shadow-lg">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold">
                        Find My Roommate
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:ms-10 sm:flex items-center">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-indigo-100 hover:bg-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('admin*') ? 'bg-indigo-800' : '' }}">
                                <i class="fas fa-tachometer-alt mr-1"></i> {{ __('Admin Dashboard') }}
                            </a>
                        @else
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-indigo-100 hover:bg-indigo-800 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-home mr-1"></i> {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-white hover:text-indigo-200 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 rounded-full overflow-hidden bg-indigo-700">
                                    @if(Auth::user()->avatar && Storage::exists(Auth::user()->avatar))
                                        <img src="{{ Storage::url(Auth::user()->avatar) }}?{{ time() }}" 
                                             alt="{{ Auth::user()->fullName() }}" 
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-indigo-600 flex items-center justify-center text-white text-sm font-medium">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>{{ Auth::user()?->first_name ?? 'User' }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('admin*') ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-2 w-5 text-center"></i> {{ __('Admin Dashboard') }}
                </a>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="fas fa-home mr-2 w-5 text-center"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full overflow-hidden bg-indigo-700">
                        @if(Auth::user()->avatar && Storage::exists(Auth::user()->avatar))
                            <img src="{{ Storage::url(Auth::user()->avatar) }}?{{ time() }}" 
                                 alt="{{ Auth::user()->fullName() }}" 
                                 class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-indigo-600 flex items-center justify-center text-white text-sm font-medium">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()?->name ?? '' }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email ?? '' }}</div>
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
    </div>
</nav>
