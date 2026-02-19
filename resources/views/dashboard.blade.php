<x-app-layout>
    <!-- Alpine.js Test Component -->
    <div x-data="{ test: 'Alpine is working!', sidebarOpen: true }" x-init="console.log('Alpine initialized:', test)" class="h-screen flex overflow-hidden bg-gray-100">
        <p x-text="test" class="hidden"></p>
        
        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="md:hidden">
            <div class="fixed inset-0 flex z-40">
                <div class="fixed inset-0" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <h1 class="text-white text-2xl font-bold">Find My Roommate</h1>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="{{ route('dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('matches.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                My Matches
                            </a>
                            <a href="{{ route('messages.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Messages
                            </a>
                            <a href="{{ route('activity.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Activity
                            </a>
                            <a href="{{ route('roommates.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Browse Roommates
                            </a>
                            <a href="{{ route('listings.create') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Create Listing
                            </a>
                            <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Profile Settings
                            </a>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                        <div class="flex items-center">
                            <div>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(Auth::user()?->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-base font-medium text-white">{{ Auth::user()?->first_name ?? 'User' }}</p>
                                        <form method="POST" action="{{ route('logout') }}" class="text-sm font-medium text-gray-400 group-hover:text-gray-300">
                                            @csrf
                                            <button type="submit" class="hover:text-white">Sign out</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-white text-2xl font-bold">Find My Roommate</h1>
                    </div>
                    <nav class="mt-5 flex-1 px-2 bg-gray-800 space-y-1">
                        <a href="{{ route('dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('matches.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            My Matches
                        </a>
                        <a href="{{ route('messages.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Messages
                        </a>
                        <a href="{{ route('activity.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Activity
                        </a>
                        <a href="{{ route('roommates.index') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Browse Roommates
                        </a>
                        <a href="{{ route('listings.create') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Create Listing
                        </a>
                        <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:bg-gray-700 hover:bg-opacity-75 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Profile Settings
                        </a>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <div class="flex items-center">
                        <div>
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()?->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-white">{{ Auth::user()?->first_name ?? 'User' }}</p>
                                    <form method="POST" action="{{ route('logout') }}" class="text-xs font-medium text-gray-300 hover:text-white">
                                        @csrf
                                        <button type="submit">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Background Section -->
    <div class="min-h-screen w-full bg-cover bg-center bg-no-repeat bg-fixed" 
         style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80'); background-size: cover; background-position: center;">
    <div class="flex-1 overflow-auto focus:outline-none">
        <!-- Profile Header - Removed white space -->

        <!-- Dashboard Content -->
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-xl text-white mb-8">Your perfect roommate is just a click away!</p>

    @push('styles')
    <style>
        html, body, #app { height: 100%; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
    @endpush
</x-app-layout>
