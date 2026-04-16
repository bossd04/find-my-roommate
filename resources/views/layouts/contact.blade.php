<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Find My Roommate') }} - @yield('title', 'Contact Support')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Simple Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                            Find My Roommate
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Sign Up</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-700">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-indigo-600">Logout</button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Simple Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Find My Roommate. All rights reserved.</p>
                    <div class="mt-4 space-x-4">
                        <a href="{{ route('about') }}" class="text-gray-400 hover:text-white">About</a>
                        <a href="{{ route('contact.support') }}" class="text-gray-400 hover:text-white">Contact</a>
                        <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white">Privacy</a>
                        <a href="{{ route('terms') }}" class="text-gray-400 hover:text-white">Terms</a>
                        <a href="{{ route('safety') }}" class="text-gray-400 hover:text-white">Safety</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
