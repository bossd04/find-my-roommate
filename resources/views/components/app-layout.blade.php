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
        
        <!-- Admin Dashboard Styles -->
        @if(request()->is('admin*'))
        <link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
        @endif
        
        <!-- Profile Page Background Override -->
        @if(request()->routeIs('profile.index'))
        <style>
            html body {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%) !important;
            }
            body {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%) !important;
            }
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%) !important;
                z-index: -1;
            }
            .min-h-screen {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%) !important;
            }
            main {
                background: transparent !important;
            }
            .flex-1 {
                background: transparent !important;
            }
        </style>
        @endif
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- CSRF Handler -->
        <script src="{{ asset('js/csrf-handler.js') }}"></script>
    </head>
    <body class="font-sans antialiased min-h-screen" 
          @if(request()->routeIs('profile.index'))
          style="background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%) !important;"
          @endif>
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
