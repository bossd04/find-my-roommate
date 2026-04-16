@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 text-center">Safety Tips</h1>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-8">
                <p class="text-yellow-800 dark:text-yellow-200 text-center font-medium">
                    Your safety is our top priority. Please read these important safety guidelines.
                </p>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">🔒</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Protect Your Personal Information</h3>
                            <p class="text-gray-600 dark:text-gray-400">Never share sensitive personal information such as your home address, financial details, or ID numbers in your profile or initial conversations. Use our platform's messaging system until you feel comfortable.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">🤝</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Meet in Public First</h3>
                            <p class="text-gray-600 dark:text-gray-400">Always arrange your first meeting in a public place like a café, library, or campus common area. Bring a friend if possible, and let someone know where you're going.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">🎥</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Video Chat Before Meeting</h3>
                            <p class="text-gray-600 dark:text-gray-400">Have a video call with potential roommates before meeting in person. This helps verify their identity and gives you a better sense of who they are.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">📝</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Trust Your Instincts</h3>
                            <p class="text-gray-600 dark:text-gray-400">If something feels off about a person or situation, trust your gut. It's better to be cautious than to put yourself in an uncomfortable or unsafe situation.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">📋</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Get Everything in Writing</h3>
                            <p class="text-gray-600 dark:text-gray-400">Before moving in together, create a roommate agreement that covers rent, utilities, chores, guests, and other important topics. Having it in writing prevents misunderstandings.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-3xl">🚨</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Report Suspicious Behavior</h3>
                            <p class="text-gray-600 dark:text-gray-400">If you encounter any suspicious behavior or feel unsafe, report it immediately using our reporting feature. We take all reports seriously and investigate promptly.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Emergency Contacts</h3>
                <p class="text-blue-700 dark:text-blue-300">If you're in immediate danger, please contact:</p>
                <ul class="list-disc list-inside text-blue-700 dark:text-blue-300 mt-2">
                    <li>Emergency Services: 911</li>
                    <li>Campus Security: Check your university's security number</li>
                    <li>Local Police: Your local non-emergency number</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
