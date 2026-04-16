<div x-show="sidebarOpen" class="flex flex-shrink-0">
    <div class="flex flex-col w-64 bg-indigo-700">
        <div class="flex flex-col h-full">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-6 py-6">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Admin Panel</span>
                </div>
                <nav class="mt-2 flex-1 px-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        Dashboard
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-users mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        Users
                    </a>

                    <!-- ID Verification -->
                    <a href="{{ route('admin.users.id-verification') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.id-verification') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-id-card mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        ID Verification
                    </a>

                    <!-- Pending Approvals -->
                    <a href="{{ route('admin.users.pending-approvals') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users.pending-approvals') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-user-clock mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        Approvals
                    </a>

                   <!-- Listings -->
                    <a href="{{ route('admin.listings.index') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.listings.*') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-home mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        Listings
                    </a>

                    <!-- Messages -->
                    <a href="{{ route('admin.messages.index') }}"
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.messages.*') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-envelope mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        Messages
                    </a>

                    <!-- User Reports -->
                    <a href="{{ route('admin.user-reports.index') }}"
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.user-reports.*') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-exclamation-triangle mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                        User Reports
                    </a>

                    <!-- Settings -->
                    <div x-data="{ open: {{ request()->routeIs('admin.settings*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="group w-full flex items-center justify-between px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.settings*') ? 'bg-white/10 text-white shadow-lg shadow-indigo-900/20' : 'text-indigo-200 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <i class="fas fa-cog mr-4 text-indigo-400 group-hover:text-white transition-colors"></i>
                                <span>Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-[8px] transition-transform duration-300" :class="{ 'rotate-90': open }"></i>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-2 space-y-1 bg-black/10 rounded-2xl p-2 mx-1">
                            <a href="{{ route('admin.settings.index') }}" 
                               class="group flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('admin.settings.index') ? 'bg-indigo-600 text-white' : 'text-indigo-300 hover:text-white hover:bg-white/5' }}">
                                <i class="fas fa-sliders-h mr-3 text-indigo-500"></i> General
                            </a>
                            <a href="{{ route('admin.settings.design') }}" 
                               class="group flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all {{ request()->routeIs('admin.settings.design') ? 'bg-indigo-600 text-white' : 'text-indigo-300 hover:text-white hover:bg-white/5' }}">
                                <i class="fas fa-palette mr-3 text-indigo-500"></i> Design
                            </a>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="py-4 px-4">
                        <div class="h-[1px] bg-indigo-500/20"></div>
                    </div>

                    <!-- Backup & Logs -->
                    <a href="{{ route('admin.activity_logs.index') }}" 
                       class="group flex items-center px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all duration-300 {{ request()->routeIs('admin.activity_logs.*') ? 'bg-white/10 text-white' : 'text-indigo-200 hover:bg-white/5' }}">
                        <i class="fas fa-history mr-4 text-indigo-400"></i> Logs
                    </a>
                </nav>
            </div>

            <!-- Profile Summary Footer (Minimalized) -->
            <div class="flex-shrink-0 flex bg-indigo-900/40 p-6">
                <div class="flex items-center w-full">
                    <div class="relative group">
                        @if(auth()->user()->profile_photo_path)
                            <img class="h-12 w-12 rounded-2xl object-cover border-2 border-indigo-500 shadow-xl transition-transform duration-500 group-hover:scale-110 sidebar-avatar" 
                                 src="{{ route('profile.photo.serve', ['filename' => basename(auth()->user()->profile_photo_path)]) }}" alt="Admin">
                        @else
                            <div class="h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center border-2 border-indigo-400 shadow-xl">
                                <span class="text-white font-black">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <span class="absolute -bottom-1 -right-1 block h-3 w-3 rounded-full bg-emerald-500 ring-4 ring-indigo-900 animate-pulse"></span>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('admin.profile.show') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300 hover:text-white transition-colors">
                            Manage Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
