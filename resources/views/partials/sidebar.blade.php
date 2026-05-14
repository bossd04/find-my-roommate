<!-- Global Sidebar Menu -->
<div class="w-64 flex-shrink-0 hidden lg:block">
    <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-4 border border-white border-opacity-50 sticky top-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
            <span class="text-2xl mr-2">🧭</span>
            Navigation
        </h3>
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300' : '' }}">
                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1h3a1 1 0 011 1v3m0 0h3m-3 0v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">Dashboard</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Main overview</div>
                </div>
            </a>

            <a href="{{ route('matches.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/40 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 group {{ request()->routeIs('matches*') ? 'bg-blue-50 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300' : '' }}">
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">My Matches</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">View your matches</div>
                </div>
            </a>

            <a href="{{ route('roommates.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/40 hover:text-green-600 dark:hover:text-green-400 transition-all duration-200 group {{ request()->routeIs('roommates*') ? 'bg-green-50 dark:bg-green-900/60 text-green-600 dark:text-green-300' : '' }}">
                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">Browse Roommates</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Browse compatible matches</div>
                </div>
            </a>

            <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/40 hover:text-purple-600 dark:hover:text-purple-400 transition-all duration-200 group {{ request()->routeIs('messages*') ? 'bg-purple-50 dark:bg-purple-900/60 text-purple-600 dark:text-purple-300' : '' }}">
                <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">Messages</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Chat with roommates</div>
                </div>
                @php
                    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('activity.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/40 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 group {{ request()->routeIs('activity*') ? 'bg-red-50 dark:bg-red-900/60 text-red-600 dark:text-red-300' : '' }}">
                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">Activity</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Recent activity</div>
                </div>
            </a>

            <!-- Theme Toggle -->
            <div id="theme-toggle-container" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                    <svg id="theme-icon-light" class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="theme-icon-dark" class="w-4 h-4 text-gray-600 dark:text-gray-300 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9 9 0 0012.707 8.293 9 9 0 0015.354 15.354zM9 12a3 3 0 106 0 3 3 0 00-6 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium">Theme</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Dark / Light</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="theme-toggle" class="sr-only peer">
                    <div class="w-12 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 shadow-inner">
                        <div class="absolute inset-0 flex items-center justify-between px-1.5 pointer-events-none">
                            <span class="text-[10px]">🌙</span>
                            <span class="text-[10px]">☀️</span>
                        </div>
                    </div>
                </label>
            </div>
        </nav>
    </div>
</div>

<!-- Add padding for mobile bottom navigation -->
<div class="lg:hidden h-16"></div>
