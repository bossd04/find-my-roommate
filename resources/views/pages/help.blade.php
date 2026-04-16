@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Help Center</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">How Can We Help?</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Find answers to common questions and learn how to use Find My Roommate effectively.
                </p>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Getting Started</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Learn how to create your profile, set your preferences, and start finding compatible roommates.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li>Creating your profile</li>
                        <li>Setting your preferences</li>
                        <li>Uploading your photo</li>
                        <li>Completing verification</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Finding Roommates</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Discover how to search for and connect with potential roommates.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li>Using the search feature</li>
                        <li>Understanding compatibility scores</li>
                        <li>Sending connection requests</li>
                        <li>Using the map search</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Messaging & Communication</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Learn how to communicate safely with potential roommates.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li>Sending messages</li>
                        <li>Chat features</li>
                        <li>Safety tips for communication</li>
                        <li>Reporting issues</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Account Management</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Manage your account settings and preferences.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li>Updating your profile</li>
                        <li>Changing password</li>
                        <li>Privacy settings</li>
                        <li>Deleting your account</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">Still need help?</p>
                <a href="{{ route('contact') }}" class="inline-block mt-2 text-blue-500 hover:text-blue-600 font-medium">Contact Support</a>
            </div>
        </div>
    </div>
</div>
@endsection
