@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-3xl font-bold text-gray-900">Privacy Policy</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Information We Collect</h2>
                    <p class="text-gray-700 mb-6">
                        We collect information you provide directly to us, such as when you create an account, fill out a profile, or contact us. This may include:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                        <li>Name, email address, and phone number</li>
                        <li>Profile information (photos, bio, preferences)</li>
                        <li>Rental preferences and budget information</li>
                        <li>Messages and communications with other users</li>
                        <li>Usage data and interaction with our platform</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. How We Use Your Information</h2>
                    <p class="text-gray-700 mb-6">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process transactions and send related information</li>
                        <li>Send technical notices and support messages</li>
                        <li>Respond to your comments, questions, and requests</li>
                        <li>Monitor and analyze trends and usage</li>
                        <li>Detect, investigate, and prevent security incidents</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Information Sharing</h2>
                    <p class="text-gray-700 mb-6">
                        We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                        <li>To trusted service providers who assist us in operating our platform</li>
                        <li>When required by law or to protect our rights</li>
                        <li>In connection with a merger, acquisition, or sale of assets</li>
                        <li>To other users when you choose to share information through our messaging system</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Data Security</h2>
                    <p class="text-gray-700 mb-6">
                        We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Cookies and Tracking</h2>
                    <p class="text-gray-700 mb-6">
                        We use cookies and similar tracking technologies to track activity on our service and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Data Retention</h2>
                    <p class="text-gray-700 mb-6">
                        We retain your personal information for as long as necessary to provide our services, comply with legal obligations, resolve disputes, and enforce our agreements.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Your Rights</h2>
                    <p class="text-gray-700 mb-6">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                        <li>Access and update your personal information</li>
                        <li>Delete your account and personal data</li>
                        <li>Opt out of marketing communications</li>
                        <li>Request a copy of your data</li>
                        <li>Object to processing of your information</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Children's Privacy</h2>
                    <p class="text-gray-700 mb-6">
                        Our service is not intended for children under 18 years of age. We do not knowingly collect personal information from children under 18. If we become aware that we have collected personal information from children under 18, we will take steps to delete such information.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. International Data Transfers</h2>
                    <p class="text-gray-700 mb-6">
                        Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with applicable data protection laws.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Changes to This Policy</h2>
                    <p class="text-gray-700 mb-6">
                        We may update this privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date.
                    </p>

                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Contact Us</h2>
                    <p class="text-gray-700 mb-6">
                        If you have any questions about this Privacy Policy, please contact us:
                    </p>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700">
                            Email: privacy@findmyroommate.com<br>
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
