@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Careers</h1>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Join Our Team</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
                    We're always looking for talented individuals who are passionate about helping students 
                    find their perfect living situations. Join us in building the future of student housing.
                </p>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Software Engineer</h3>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mb-3">Full-time • Remote</p>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Help us build and scale our platform to serve more students across the country.</p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Apply Now</button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Marketing Specialist</h3>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mb-3">Full-time • On-site</p>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Spread the word about Find My Roommate to universities and students nationwide.</p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Apply Now</button>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Customer Support</h3>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mb-3">Part-time • Remote</p>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Help our users have the best experience possible with their roommate search.</p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
