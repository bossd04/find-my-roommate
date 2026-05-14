@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Community Guidelines</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Our Commitment to a Safe Community</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Find My Roommate is committed to creating a safe, respectful, and inclusive community for all users. 
                    These guidelines help ensure everyone has a positive experience while using our platform.
                </p>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Be Respectful</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Treat all users with respect and courtesy. Discrimination, harassment, or hate speech of any kind is not tolerated.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1">
                        <li>No discriminatory language or behavior</li>
                        <li>No harassment or bullying</li>
                        <li>Respect different backgrounds and lifestyles</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Be Honest</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Accuracy and honesty are essential for finding compatible roommates.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1">
                        <li>Provide accurate information in your profile</li>
                        <li>Use your real photos</li>
                        <li>Be truthful about your habits and preferences</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Be Safe</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Prioritize safety in all your interactions on and off the platform.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1">
                        <li>Meet in public places first</li>
                        <li>Don't share sensitive personal information</li>
                        <li>Report suspicious behavior</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">No Spam or Scams</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">We do not tolerate spam, scams, or fraudulent activity.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1">
                        <li>No commercial solicitation</li>
                        <li>No fraudulent listings or offers</li>
                        <li>No phishing attempts</li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Appropriate Content Only</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">All content on the platform must be appropriate and respectful.</p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-1">
                        <li>No explicit or inappropriate photos</li>
                        <li>No offensive language in profiles</li>
                        <li>No illegal content or activities</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">Violations</h3>
                <p class="text-red-700 dark:text-red-300">
                    Violations of these guidelines may result in account suspension or permanent ban. 
                    We reserve the right to remove any content or user that violates these guidelines.
                </p>
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">Questions about our guidelines?</p>
                <a href="{{ route('contact') }}" class="inline-block mt-2 text-blue-500 hover:text-blue-600 font-medium">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
