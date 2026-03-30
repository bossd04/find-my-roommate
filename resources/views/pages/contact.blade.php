@extends('layouts.guest')

@section('title', 'Contact Support')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-3xl font-bold text-gray-900">Contact Support</h1>
                <p class="mt-2 text-sm text-gray-500">We're here to help you with any questions or issues.</p>
            </div>
            
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mx-4 mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mx-4 mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Get in Touch</h2>
                    
                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                                <i class="fas fa-envelope mr-2"></i>Email Support
                            </h3>
                            <p class="text-gray-700 mb-4">
                                Send us an email and we'll respond within 24 hours.
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">Email:</span>
                                    <a href="mailto:support@findmyroommate.com" class="text-blue-600 hover:text-blue-500">
                                        support@findmyroommate.com
                                    </a>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">Response Time:</span>
                                    <span class="text-gray-600">Within 24 hours</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-900 mb-4">
                                <i class="fas fa-phone mr-2"></i>Phone Support
                            </h3>
                            <p class="text-gray-700 mb-4">
                                Call us for immediate assistance during business hours.
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">Phone:</span>
                                    <a href="tel:+639556076938" class="text-green-600 hover:text-green-500">
                                        +63 955-607-6938
                                    </a>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">Hours:</span>
                                    <span class="text-gray-600">Mon-Fri, 9AM-6PM PST</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Office Location -->
                    <div class="bg-yellow-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>Office Location
                        </h3>
                        <div class="text-gray-700">
                            <p class="mb-2">
                                <strong>Find My Roommate</strong><br>
                                Dagupan City, Pangasinan<br>
                                Philippines
                            </p>
                            <p class="text-sm text-gray-600">
                                Visit us for in-person support or to schedule a consultation.
                            </p>
                        </div>
                    </div>

                    <!-- Common Issues -->
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    
                    <div class="space-y-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <i class="fas fa-question-circle text-indigo-600 mr-2"></i>
                                How long does account approval take?
                            </h3>
                            <p class="text-gray-700">
                                Account approval typically takes 1-2 business hours. You'll receive an email notification once your account is approved.
                            </p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <i class="fas fa-user-clock text-indigo-600 mr-2"></i>
                                Why is my account pending approval?
                            </h3>
                            <p class="text-gray-700">
                                We review all new accounts to ensure the safety and quality of our platform. This helps prevent fraud and maintains a trustworthy community.
                            </p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <i class="fas fa-key text-indigo-600 mr-2"></i>
                                I forgot my password, what should I do?
                            </h3>
                            <p class="text-gray-700">
                                Visit the <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500">forgot password page</a> to reset your password using your email address.
                            </p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>
                                How do I report inappropriate content?
                            </h3>
                            <p class="text-gray-700">
                                If you encounter inappropriate content or behavior, please report it immediately through our contact channels or use the report feature within the platform.
                            </p>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-paper-plane text-indigo-600 mr-2"></i>
                            Send us a Message
                        </h3>
                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Name
                                    </label>
                                    <input type="text" id="name" name="name" required
                                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="John Doe">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Email
                                    </label>
                                    <input type="email" id="email" name="email" required
                                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="you@example.com">
                                </div>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject
                                </label>
                                <input type="text" id="subject" name="subject" required
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="How can we help you?">
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea id="message" name="message" rows="4" required
                                          class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Describe your issue or question..."></textarea>
                            </div>

                            <div>
                                <button type="submit" 
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ url()->previous() ?: route('home') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                ← Back to Previous Page
            </a>
        </div>
    </div>
</div>
@endsection
