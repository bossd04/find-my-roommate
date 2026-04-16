@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">System Settings</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    Manage your application settings and configurations
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('admin.settings.design') }}" class="group flex items-center px-6 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-palette mr-3 group-hover:scale-110 transition-transform"></i>
                    Design Settings
                </a>
            </div>
        </div>
        
        <!-- Decorative Background Element -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Navigation Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-lg font-black text-gray-900 dark:text-gray-50">Settings Menu</h2>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">Navigation</p>
                </div>
                <nav class="p-4 space-y-2">
                    <a href="{{ route('admin.settings') }}" class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-cog mr-3"></i>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.design') }}" class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all">
                        <i class="fas fa-palette mr-3"></i>
                        Design & Theme
                    </a>
                    <a href="{{ route('admin.settings.system') }}" class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all">
                        <i class="fas fa-server mr-3"></i>
                        System Information
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </a>
                </nav>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-xs font-black uppercase tracking-widest rounded-xl text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-4 focus:ring-amber-500/20 transition-all shadow-lg shadow-amber-500/20">
                            <i class="fas fa-sync-alt mr-2"></i> Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-xl font-black text-gray-900 dark:text-gray-50 mb-2">General Settings</h2>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Core Application Configuration</p>
                </div>
                
                <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-8">
                        <!-- Application Name -->
                        <div class="space-y-3">
                            <label for="app_name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Application Name</label>
                            <div class="relative">
                                <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}" 
                                       class="block w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-sm font-black text-gray-900 dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- Application URL -->
                        <div class="space-y-3">
                            <label for="app_url" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Application URL</label>
                            <div class="relative">
                                <input type="url" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}" 
                                       class="block w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-sm font-black text-gray-900 dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- Timezone -->
                        <div class="space-y-3">
                            <label for="timezone" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Timezone</label>
                            <div class="relative">
                                <select id="timezone" name="timezone" 
                                        class="filter-dropdown w-full appearance-none px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 font-black text-sm text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" {{ old('timezone', config('app.timezone')) === $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="dropdown-arrow absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Email From Address -->
                        <div class="space-y-3">
                            <label for="mail_from_address" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email From Address</label>
                            <div class="relative">
                                <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', config('mail.from.address')) }}" 
                                       class="block w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-sm font-black text-gray-900 dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- Email From Name -->
                        <div class="space-y-3">
                            <label for="mail_from_name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email From Name</label>
                            <div class="relative">
                                <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', config('mail.from.name')) }}" 
                                       class="block w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-sm font-black text-gray-900 dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-8 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('admin.settings') }}" class="px-8 py-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all">
                                    Cancel
                                </a>
                                <button type="submit" class="px-8 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-lg shadow-indigo-500/30">
                                    <i class="fas fa-save mr-3"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
