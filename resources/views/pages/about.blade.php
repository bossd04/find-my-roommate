@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">About Us</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Find My Roommate</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    Find My Roommate is a platform designed to help students find compatible roommates for their university journey. 
                    We understand that finding the right roommate can make or break your living experience, which is why we've 
                    created a comprehensive matching system that considers lifestyle, preferences, and compatibility.
                </p>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Our mission is to make the roommate search process simple, safe, and successful for every student.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                    <div class="text-4xl mb-4">🎯</div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Smart Matching</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Our algorithm matches you with compatible roommates based on your preferences.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Safe & Secure</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Verified profiles and secure messaging to keep you protected.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                    <div class="text-4xl mb-4">💬</div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Easy Communication</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Built-in messaging to connect and chat with potential roommates.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
