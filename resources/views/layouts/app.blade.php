<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Global Theme Background - Only on specific pages -->
        @if(request()->is('/') || request()->is('dashboard') || request()->is('home') || request()->is('matches') || request()->is('matches/*') || request()->is('roommates') || request()->is('roommates/*') || request()->is('listings/create') || request()->is('activity'))
        <link href="{{ asset('css/global-theme.css') }}?v=3" rel="stylesheet">
        @endif
        
        <!-- Admin Dashboard Styles -->
        @if(request()->is('admin*'))
        <link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
        @endif
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- CSRF Handler -->
        <script src="{{ asset('js/csrf-handler.js') }}"></script>
        
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" />
        
        <!-- Leaflet JS -->
        <script src="{{ asset('js/leaflet.js') }}"></script>
    </head>
    <body class="font-sans antialiased min-h-screen">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-1">
                @if(isset($slot) && trim($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
        
        <!-- Theme Toggle Script -->
        <script>
        // Theme Toggle Functionality
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIconLight = document.getElementById('theme-icon-light');
            const themeIconDark = document.getElementById('theme-icon-dark');
            const themeToggleContainer = document.getElementById('theme-toggle-container');
            
            // If theme elements don't exist, exit early
            if (!themeToggle && !themeToggleContainer) {
                return;
            }
            
            // Check for saved theme preference or default to light
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                if (themeToggle) themeToggle.checked = true;
                if (themeIconLight) themeIconLight.classList.add('hidden');
                if (themeIconDark) themeIconDark.classList.remove('hidden');
            }
            
            // Listen for toggle changes
            if (themeToggle) {
                themeToggle.addEventListener('change', function() {
                    if (this.checked) {
                        document.body.classList.add('dark-mode');
                        localStorage.setItem('theme', 'dark');
                        if (themeIconLight) themeIconLight.classList.add('hidden');
                        if (themeIconDark) themeIconDark.classList.remove('hidden');
                    } else {
                        document.body.classList.remove('dark-mode');
                        localStorage.setItem('theme', 'light');
                        if (themeIconLight) themeIconLight.classList.remove('hidden');
                        if (themeIconDark) themeIconDark.classList.add('hidden');
                    }
                });
            }
            
            // Also handle click on the container
            if (themeToggleContainer) {
                themeToggleContainer.addEventListener('click', function() {
                    if (themeToggle) {
                        themeToggle.checked = !themeToggle.checked;
                        themeToggle.dispatchEvent(new Event('change'));
                    }
                });
            }
        })();
        </script>
        
        @stack('scripts')
    </body>
</html>
