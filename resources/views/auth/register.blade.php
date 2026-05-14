@extends('layouts.guest')

@section('content')
<!-- Logo -->
<div class="flex justify-center mb-8">
    <a href="/" class="flex items-center space-x-2">
        <div class="w-12 h-12 rounded-lg bg-indigo-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Find My Roommate
        </span>
    </a>
</div>

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!-- Register Form -->
<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- First Name -->
        <div class="space-y-2">
            <x-input-label for="first_name" :value="__('First Name')" class="text-sm font-medium text-gray-700" />
            <div class="mt-1">
                <input id="first_name" name="first_name" type="text" required autofocus 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                       placeholder="John">
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
        </div>

        <!-- Last Name -->
        <div class="space-y-2">
            <x-input-label for="last_name" :value="__('Last Name')" class="text-sm font-medium text-gray-700" />
            <div class="mt-1">
                <input id="last_name" name="last_name" type="text" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                       placeholder="Doe">
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>
    </div>

    <!-- Email -->
    <div class="space-y-2">
        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
        <div class="mt-1">
            <input id="email" name="email" type="email" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                   placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    </div>

    <!-- Password -->
    <div class="space-y-2">
        <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
        <div class="mt-1">
            <input id="password" name="password" type="password" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="space-y-2">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
        <div class="mt-1">
            <input id="password_confirmation" name="password_confirmation" type="password" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
    </div>

    <!-- Terms & Conditions -->
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input id="terms" name="terms" type="checkbox" required
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        </div>
        <div class="ml-3 text-sm">
            <label for="terms" class="font-medium text-gray-700">I agree to the</label>
            <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline"> Terms of Service </a> and 
            <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">Privacy Policy</a>
        </div>
    </div>

    <div>
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
            Create Account
        </button>
    </div>

    <div class="text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Sign in
        </a>
    </div>
</form>
@endsection
