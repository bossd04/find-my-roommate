@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Blog</h1>
            
            <div class="space-y-8">
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">April 5, 2024</p>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">5 Tips for Finding the Perfect Roommate</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Finding the right roommate can make your university experience amazing. Here are our top tips for a successful roommate search...</p>
                        <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Read More →</a>
                    </div>
                </article>

                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">March 28, 2024</p>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">How to Create a Great Roommate Profile</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Your profile is your first impression. Learn how to make it stand out and attract compatible roommates...</p>
                        <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Read More →</a>
                    </div>
                </article>

                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-2">March 15, 2024</p>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Roommate Agreements: What to Include</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">A good roommate agreement can prevent conflicts. Here's what you should discuss before moving in together...</p>
                        <a href="#" class="text-blue-500 hover:text-blue-600 font-medium">Read More →</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection
