@extends('layouts.guest')

@section('content')
<!-- Logo -->
<div class="flex justify-center mb-8">
    <a href="/" class="flex items-center space-x-2">
        <div class="w-12 h-12 rounded-lg bg-indigo-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Find My Roommate
        </span>
    </a>
</div>

<div class="mb-6 text-center text-gray-700">
    <h2 class="text-2xl font-bold mb-2">Forgot your password?</h2>
    <p class="text-sm">No worries! Enter your email and we'll send you a password reset link.</p>
</div>

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!-- Forgot Password Form -->
<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <div class="space-y-2">
        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
        <div class="mt-1">
            <input id="email" name="email" type="email" required autofocus 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                   placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    </div>

    <div>
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
            Send Reset Link
        </button>
    </div>

    <div class="text-center text-sm text-gray-600">
        Remember your password?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Sign in here
        </a>
    </div>
</form>

<div class="mt-6 text-center text-xs text-gray-500">
    <p>We'll send you a link to reset your password.</p>
</div>
@endsection
