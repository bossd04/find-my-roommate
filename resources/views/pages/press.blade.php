@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Press & Media</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Media Inquiries</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    For press inquiries, interviews, or media kit requests, please contact our press team.
                </p>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> press@findmyroommate.com</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Phone:</strong> +1 (555) 123-4567</p>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Recent News</h2>
            
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">March 15, 2024</p>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Find My Roommate Launches New Mobile App</h3>
                    <p class="text-gray-600 dark:text-gray-400">Our new mobile app makes it even easier for students to find roommates on the go.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">February 28, 2024</p>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Partnership with 50+ Universities Announced</h3>
                    <p class="text-gray-600 dark:text-gray-400">We're proud to partner with over 50 universities to help their students find compatible roommates.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">January 10, 2024</p>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Feature Update: AI-Powered Matching</h3>
                    <p class="text-gray-600 dark:text-gray-400">Introducing our new AI-powered matching algorithm for even better roommate compatibility.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
