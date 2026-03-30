@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-3xl font-bold text-gray-900">Terms of Service</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                    <p class="text-gray-700 mb-6">
                        By accessing and using Find My Roommate, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
                    <p class="text-gray-700 mb-6">
                        Find My Roommate is a platform designed to help individuals find compatible roommates and rental accommodations. Our service includes user profiles, matching algorithms, messaging systems, and listing management tools.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. User Registration</h2>
                    <p class="text-gray-700 mb-6">
                        To use our service, you must register for an account. You must provide accurate, complete, and current information. You are responsible for safeguarding your account credentials and for all activities that occur under your account.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. User Conduct</h2>
                    <p class="text-gray-700 mb-6">
                        You agree not to use the service to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                        <li>Post false, misleading, or fraudulent information</li>
                        <li>Impersonate any person or entity</li>
                        <li>Harass, abuse, or harm other users</li>
                        <li>Post inappropriate or offensive content</li>
                        <li>Violate any applicable laws or regulations</li>
                        <li>Attempt to gain unauthorized access to our systems</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Privacy and Data Protection</h2>
                    <p class="text-gray-700 mb-6">
                        Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the service, to understand our practices regarding the collection and use of your information.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Account Termination</h2>
                    <p class="text-gray-700 mb-6">
                        We reserve the right to suspend or terminate your account if you violate these terms or engage in fraudulent or illegal activities. You may also terminate your account at any time through your account settings.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Disclaimers</h2>
                    <p class="text-gray-700 mb-6">
                        Find My Roommate is not responsible for the conduct of users or the quality of listings. We do not conduct background checks on users. You are solely responsible for your interactions with other users and any rental agreements you enter into.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Limitation of Liability</h2>
                    <p class="text-gray-700 mb-6">
                        Find My Roommate shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Changes to Terms</h2>
                    <p class="text-gray-700 mb-6">
                        We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of the service constitutes acceptance of any changes.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Contact Information</h2>
                    <p class="text-gray-700 mb-6">
                        If you have any questions about these Terms of Service, please contact us at:
                    </p>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700">
                            Email: support@findmyroommate.com<br>
                            Phone: 09556076938<br>
                            Address: Dagupan City, Pangasinan
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                ← Back to Registration
            </a>
        </div>
    </div>
</div>
@endsection
