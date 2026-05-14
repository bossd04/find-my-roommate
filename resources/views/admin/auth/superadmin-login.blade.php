@extends('admin.layouts.auth')

@section('content')
<div class="login-card">
    <!-- Crown Icon -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/5 border border-white/10 mb-4">
            <i class="fas fa-crown text-4xl" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.5));"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Super Admin</h1>
        <p class="text-purple-200 text-sm mt-1">Restricted Access Portal</p>
    </div>

    <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-200 text-sm font-medium"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <!-- Session Status -->
        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-xl">
                <p class="text-emerald-200 text-sm font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('status') }}</p>
            </div>
        @endif
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('superadmin.login.submit') }}" class="space-y-5">
            @csrf
            
            <!-- Email -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-purple-200 mb-2">
                    Email Address
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-300">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="text" name="email" value="superadmin@example.com" required autofocus
                           class="input-field w-full pl-11 pr-4 py-3.5 rounded-xl text-white bg-white/10 border border-white/20 focus:outline-none focus:border-white/40">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-purple-200 mb-2">
                    Password
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-300">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="text" name="password" value="superadmin123" required
                           class="input-field w-full pl-11 pr-4 py-3.5 rounded-xl text-white bg-white/10 border border-white/20 focus:outline-none focus:border-white/40">
                </div>
            </div>
            
            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-purple-400/50 bg-white/5 text-purple-600 focus:ring-purple-500 focus:ring-offset-0">
                    <span class="ml-2 text-sm text-purple-200">Remember me</span>
                </label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 rounded-xl text-white font-bold uppercase tracking-wider flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-purple-500/30 border border-white/20">
                <i class="fas fa-shield-alt"></i>
                Access Super Admin
            </button>
        </form>
        
        <!-- Footer Links -->
        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-white text-xs">
                <i class="fas fa-lock mr-1"></i>
                Secure authentication required
            </p>
            <div class="mt-4 flex items-center justify-center gap-4 text-xs">
                <a href="{{ route('admin.login') }}" class="text-purple-300 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Regular Admin Login
                </a>
                <span class="text-purple-500">|</span>
                <a href="{{ route('password.request') }}" class="text-purple-300 hover:text-white transition-colors">
                    Forgot Password?
                </a>
            </div>
        </div>
        
        <!-- Brand -->
        <div class="mt-6 text-center">
            <p class="text-white text-[10px] uppercase tracking-[0.3em] font-bold">
                Find My Roommate
            </p>
        </div>
    </div>
</div>

<!-- Security Badge -->
<div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2 text-white/40 text-xs">
    <i class="fas fa-shield-alt"></i>
    <span>Enterprise-grade security</span>
</div>
@endsection
