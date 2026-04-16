<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Admin Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Additional Styles -->
    @stack('styles')
</head>
    <style>
        :root {
            --app-bg: url("{{ asset('images/bg-find-my-roommate.png') }}");
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
        
        body {
            background-image: var(--app-bg);
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .dark body {
            background-image: linear-gradient(rgba(5, 5, 5, 0.7), rgba(15, 15, 15, 0.7)), var(--app-bg);
        }

        /* Custom Scrollbar for modern look */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
    </style>
    
    <script>
        // Check for theme in local storage or system preference
        try {
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('admin-theme') === 'dark' || (!('admin-theme' in localStorage) && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        } catch (e) {
            console.warn('Theme initialization failed:', e);
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300" x-data="{ 
        sidebarOpen: true, 
        theme: (typeof localStorage !== 'undefined' && localStorage.getItem('admin-theme')) || 'light',
        toggleTheme() {
            if (typeof this.theme !== 'string') return;
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('admin-theme', this.theme);
            }
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    }" :class="{ 'dark': theme === 'dark' }">
    <div class="min-h-screen flex">
        <!-- Sidebar - Sticky to stay at top -->
        <div class="sticky top-0 h-screen flex-shrink-0 z-40">
            @include('admin.layouts.sidebar')
        </div>
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Navigation Header -->
            <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 z-30 sticky top-0">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 focus:outline-none md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <!-- Logo/Text Removed per user request -->
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" 
                            class="p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas" :class="theme === 'dark' ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-indigo-600 transition-colors relative">
                            <i class="far fa-bell text-lg"></i>
                            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white/80 dark:ring-gray-800/80"></span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-sm font-black text-gray-900 dark:text-gray-50">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <a href="{{ route('admin.users.pending-approvals') }}" class="flex items-start px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                                            <i class="fas fa-user-clock text-amber-600 dark:text-amber-400 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $pendingApprovalsCount ?? 0 }} Pending Approvals</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Users waiting for approval</p>
                                    </div>
                                </a>
                                <a href="{{ route('admin.users.id-verification') }}" class="flex items-start px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                            <i class="fas fa-id-card text-indigo-600 dark:text-indigo-400 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-50">{{ $pendingIdVerificationsCount ?? 0 }} ID Verifications</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending identity verification</p>
                                    </div>
                                </a>
                                <div class="px-4 py-3 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">No more notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-4 p-1 rounded-lg transition-all focus:outline-none">
                            <div class="flex flex-col text-right hidden sm:block">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Super Admin</span>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-indigo-100 border-2 border-indigo-200 overflow-hidden shadow-sm">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ route('profile.photo.serve', ['filename' => basename(auth()->user()->profile_photo_path)]) }}" alt="Admin" class="h-full w-full object-cover nav-avatar">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-indigo-600 font-bold">
                                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('admin.profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                <i class="far fa-user mr-3 text-indigo-500"></i> My Profile
                            </a>
                            <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                <i class="fas fa-cog mr-3 text-indigo-500"></i> Settings
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Scroll Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto custom-scrollbar p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <p class="font-medium font-bold text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mr-3 mt-1"></i>
                            <ul class="list-disc list-inside text-sm font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
