@extends('layouts.guest')

@section('content')
<!-- Logo -->
<div class="flex justify-center mb-8">
    <a href="/" class="flex items-center space-x-2">
        <div class="w-12 h-12 rounded-lg bg-indigo-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </div>
        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Find My Roommate
        </span>
    </a>
</div>

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!-- Login Form -->
<form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           value="{{ old('email') }}" autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                            Forgot your password?
                        </a>
                    @endif
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                            Are you an admin? Sign in here
                        </a>
                    </div>
                </div>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" 
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    {{ __('Remember me') }}
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    Sign in
                </button>
            </div>
</form>

<!-- Sign Up Link -->
<div class="mt-6 space-y-4">
    <p class="text-center text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Sign up
        </a>
    </p>
    <p class="text-center text-sm text-gray-600">
        <a href="{{ route('admin.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Are you an admin? Sign in here
        </a>
    </p>
</div>
@endsection
