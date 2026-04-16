@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="relative overflow-hidden bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Design & Theme</h1>
                    <p class="mt-2 text-sm font-bold text-gray-500 uppercase tracking-widest">Customize the look and feel of your admin dashboard.</p>
                </div>
                <div class="mt-6 md:mt-0 flex items-center bg-indigo-50 dark:bg-indigo-900/20 px-6 py-3 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest leading-none mr-3">Active Skin:</span>
                    <span class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest" x-text="theme.charAt(0).toUpperCase() + theme.slice(1) + ' Mode'"></span>
                </div>
            </div>
        </div>

        <!-- Theme Preview Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Color Mode Selection -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Select Color Mode</h3>
                <div class="grid grid-cols-2 gap-6">
                    <button onclick="updateTheme('light')" 
                            class="group flex flex-col items-center p-4 border-2 rounded-2xl transition-all duration-300 hover:shadow-xl" 
                            :class="theme === 'light' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700'">
                        <div class="w-full aspect-[4/3] bg-white rounded-xl border border-gray-200 mb-4 flex flex-col p-3 space-y-2 overflow-hidden">
                            <div class="h-2 w-1/2 bg-gray-200 rounded-full"></div>
                            <div class="h-2 w-3/4 bg-gray-100 rounded-full"></div>
                            <div class="flex-1 bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-2">
                                <div class="h-full w-full bg-white rounded-md"></div>
                            </div>
                        </div>
                        <span class="font-black text-xs uppercase tracking-widest text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Light Mode</span>
                    </button>
                    
                    <button onclick="updateTheme('dark')" 
                            class="group flex flex-col items-center p-4 border-2 rounded-2xl transition-all duration-300 hover:shadow-xl" 
                            :class="theme === 'dark' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700'">
                        <div class="w-full aspect-[4/3] bg-gray-900 rounded-xl border border-gray-700 mb-4 flex flex-col p-3 space-y-2 overflow-hidden">
                            <div class="h-2 w-1/2 bg-gray-700 rounded-full"></div>
                            <div class="h-2 w-3/4 bg-gray-600 rounded-full"></div>
                            <div class="flex-1 bg-gray-800 rounded-lg shadow-sm border border-gray-600 p-2">
                                <div class="h-full w-full bg-gray-900 rounded-md"></div>
                            </div>
                        </div>
                        <span class="font-black text-xs uppercase tracking-widest text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Dark Mode</span>
                    </button>
                </div>
            </div>

            <!-- Platform Branding -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Global Branding</h3>
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden group border border-gray-100 dark:border-gray-700 shadow-sm">
                    <img src="{{ asset('images/bg-find-my-roommate.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Platform Background">
                    <div class="absolute inset-0 bg-indigo-900/40 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                        <div class="px-6 py-3 bg-white text-gray-900 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            Apply Globally
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-relaxed">
                    This signature background maintains brand identity across all administration sectors.
                </p>
            </div>
        </div>

        <!-- Detailed Configuration Form -->
        <form action="{{ route('admin.settings.update-design') }}" method="POST" class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="p-8 space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Default Theme Option -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Default Application Theme</label>
                        <div class="relative">
                            <select name="app_theme" 
                                    class="filter-dropdown w-full appearance-none px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 font-black text-sm text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer"
                                    onchange="previewTheme(this.value)">
                                <option value="light" {{ $appTheme === 'light' ? 'selected' : '' }}>Light Mode Only</option>
                                <option value="dark" {{ $appTheme === 'dark' ? 'selected' : '' }}>Dark Mode Only</option>
                                <option value="system" {{ $appTheme === 'system' ? 'selected' : '' }}>Follow OS Preference</option>
                            </select>
                            <div class="dropdown-arrow absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Color Picker -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Accent Brand Color</label>
                        <div class="flex items-center space-x-6 p-2 bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-transparent">
                            <input type="color" name="primary_color" value="{{ $primaryColor }}" 
                                   class="h-12 w-12 rounded-xl border-none bg-transparent cursor-pointer overflow-hidden transform scale-110">
                            <input type="text" value="{{ $primaryColor }}" disabled 
                                   class="flex-1 bg-transparent border-none font-black text-sm text-gray-500 uppercase tracking-widest">
                        </div>
                    </div>

                    <!-- Sidebar Configuration -->
                    <div class="space-y-3 col-span-full">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Navigation Layout</label>
                        <div class="grid grid-cols-2 gap-6">
                            <label class="relative group cursor-pointer">
                                <input type="radio" name="sidebar_mode" value="expanded" {{ $sidebarMode === 'expanded' ? 'checked' : '' }} class="sr-only peer">
                                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent rounded-2xl font-black text-xs uppercase tracking-widest text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:text-indigo-600 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i class="fas fa-columns mr-3"></i> Expanded Sidebar
                                </div>
                            </label>
                            <label class="relative group cursor-pointer">
                                <input type="radio" name="sidebar_mode" value="compact" {{ $sidebarMode === 'compact' ? 'checked' : '' }} class="sr-only peer">
                                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent rounded-2xl font-black text-xs uppercase tracking-widest text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:text-indigo-600 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <i class="fas fa-minus mr-3"></i> Compact Mode
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all active:scale-95">
                    <i class="fas fa-check mr-3"></i> Apply Theme Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateTheme(mode) {
        localStorage.setItem('admin-theme', mode);
        document.documentElement.classList.toggle('dark', mode === 'dark');
        
        // Update Alpine theme state
        if (window.Alpine && window.Alpine.store) {
            window.Alpine.store('theme', mode);
        }
        
        // Update the theme display in the header
        updateThemeDisplay(mode);
    }
    
    function previewTheme(theme) {
        // Preview theme without saving
        if (theme === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', prefersDark);
            updateThemeDisplay(prefersDark ? 'dark' : 'light');
        } else {
            document.documentElement.classList.toggle('dark', theme === 'dark');
            updateThemeDisplay(theme);
        }
    }
    
    function updateThemeDisplay(theme) {
        // Update the active theme display in the header
        const themeDisplay = document.querySelector('[x-text*="theme"]');
        if (themeDisplay) {
            themeDisplay.textContent = theme.charAt(0).toUpperCase() + theme.slice(1) + ' Mode';
        }
        
        // Update Alpine theme state if available
        if (window.Alpine && window.Alpine.store) {
            window.Alpine.store('theme', theme);
        }
        
        // Update the Alpine data property if available
        if (window.Alpine && window.Alpine.$data) {
            const bodyElement = document.body;
            if (bodyElement._x_dataStack && bodyElement._x_dataStack[0]) {
                bodyElement._x_dataStack[0].theme = theme;
            }
        }
    }
    
    // Initialize theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentTheme = localStorage.getItem('admin-theme') || 'light';
        updateThemeDisplay(currentTheme);
        
        // Set initial theme based on system preference if needed
        const appThemeSelect = document.querySelector('select[name="app_theme"]');
        if (appThemeSelect && appThemeSelect.value === 'system') {
            previewTheme('system');
        }
        
        // Listen for system theme changes
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', function(e) {
            const appThemeSelect = document.querySelector('select[name="app_theme"]');
            if (appThemeSelect && appThemeSelect.value === 'system') {
                previewTheme('system');
            }
        });
    });
</script>
@endsection
