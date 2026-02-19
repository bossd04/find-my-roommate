<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <!-- Include your CSS and JS files here -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar-collapsed {
            width: 4rem;
        }
        .sidebar-expanded {
            width: 16rem;
        }
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar {
            transition: width 0.3s ease-in-out;
            height: 100vh;
            position: fixed;
            z-index: 40;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 30;
        }
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16rem;
            }
            .sidebar-collapsed + .main-content {
                margin-left: 4rem;
            }
        }
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar-mobile-show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 h-full">
    <!-- Mobile overlay (hidden by default) -->
    <div id="sidebar-overlay" class="overlay" onclick="toggleSidebar()"></div>
    
    <div class="flex h-full">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-indigo-700 text-white sidebar-expanded py-7 px-2 fixed inset-y-0 left-0">
            <!-- Toggle Button -->
            <div class="flex justify-end px-4 mb-6">
                <button id="sidebar-toggle" class="text-white focus:outline-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <div class="text-white flex items-center space-x-2 px-4">
                <span class="text-2xl font-semibold">Admin Panel</span>
            </div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-600 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-users mr-2"></i>Users
                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full ml-2">{{ \App\Models\User::count() }}</span>
                </a>
                <!-- Add more navigation items as needed -->
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 flex flex-col min-h-screen w-full">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button class="text-gray-500 focus:outline-none md:hidden" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800 ml-4">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- User dropdown -->
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center text-sm rounded-full focus:outline-none">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="ml-2 text-gray-700 hidden md:inline">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-gray-500 hidden md:inline"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isMobile = window.innerWidth < 768;
            
            if (isMobile) {
                // Mobile behavior
                sidebar.classList.toggle('sidebar-mobile-show');
                overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
                document.body.style.overflow = overlay.style.display === 'block' ? 'hidden' : '';
            } else {
                // Desktop behavior - toggle between expanded and collapsed
                sidebar.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-expanded');
                
                // Toggle icon
                const toggleIcon = document.querySelector('#sidebar-toggle i');
                if (toggleIcon) {
                    toggleIcon.className = sidebar.classList.contains('sidebar-collapsed') ? 'fas fa-bars' : 'fas fa-times';
                }
                
                // Save state in localStorage
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }
        
        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth >= 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                document.querySelector('#sidebar-toggle i').className = 'fas fa-bars';
            }
            
            // Close sidebar when clicking on a nav item on mobile
            const navItems = document.querySelectorAll('#sidebar nav a');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 768) {
                // On desktop, ensure sidebar is visible
                sidebar.classList.remove('sidebar-mobile-show');
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            } else {
                // On mobile, ensure sidebar is hidden by default
                if (!sidebar.classList.contains('sidebar-mobile-show')) {
                    sidebar.classList.add('sidebar-mobile-show');
                }
            }
        });
        
        // Toggle user dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-dropdown').classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const button = document.getElementById('user-menu-button');
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
