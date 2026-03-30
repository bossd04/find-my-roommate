@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $profile = $user ? $user->roommateProfile : null;
    
    // Use new isVerified() method
    $isVerified = $user && $user->isVerified();
    
    // Check if user has pending verification
    $hasPendingVerification = $user && $user->userValidation && $user->userValidation->status === 'pending';
    
    // Use new isProfileComplete() method
    $profileComplete = $user && $user->isProfileComplete();
    
    $canAccessSystem = $isVerified && $profileComplete;
    
    // Get verification status for display
    $verificationStatus = $isVerified ? 'verified' : ($hasPendingVerification ? 'pending' : 'not_submitted');
@endphp

@if(!$canAccessSystem)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">
                @if(!$isVerified && !$profileComplete)
                    Complete Profile & Verify ID to Access All Features
                @elseif(!$isVerified)
                    Verify Your ID to Access All Features  
                @else
                    Complete Your Profile to Access All Features
                @endif
            </h3>
            <div class="mt-2 text-sm text-yellow-700">
                @if(!$isVerified && !$profileComplete)
                    <p>Please complete your profile information and verify your ID to unlock all roommate finding features.</p>
                @elseif(!$isVerified)
                    <p>Your profile is complete! Please verify your ID to unlock all roommate finding features.</p>
                @else
                    <p>Please complete your profile information to unlock all roommate finding features.</p>
                @endif
                
                <!-- Debug Info Section -->
                <div class="mt-4 p-3 bg-yellow-100 rounded-md border border-yellow-200">
                    <h5 class="text-xs font-medium text-yellow-800 mb-2">🔍 Debug Information</h5>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <div><strong>ID Verified:</strong> {{ $isVerified ? 'Yes ✅' : 'No ❌' }}</div>
                        <div><strong>Profile Complete:</strong> {{ $profileComplete ? 'Yes ✅' : 'No ❌' }}</div>
                        <div><strong>Can Access System:</strong> {{ $canAccessSystem ? 'Yes ✅' : 'No ❌' }}</div>
                <div class="space-y-2 mt-4">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border @if($isVerified) border-green-200 @elseif($hasPendingVerification) border-yellow-200 @else border-red-200 @endif">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center @if($isVerified) bg-green-100 @elseif($hasPendingVerification) bg-yellow-100 @else bg-red-100 @endif mr-3">
                                @if($isVerified)
                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @elseif($hasPendingVerification)
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">ID Verification</h4>
                                <p class="text-sm text-gray-600">
                                    @if($isVerified)
                                        Verified ✅
                                    @elseif($hasPendingVerification)
                                        Pending ⏳ - Waiting for admin approval
                                    @else
                                        Not submitted - Required to access system
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if(!$isVerified && !$hasPendingVerification)
                            <a href="{{ route('validation.create') }}" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                Submit ID
                            </a>
                        @elseif($hasPendingVerification)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                Pending
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border {{ $profileComplete ? 'border-green-200' : 'border-red-200' }}">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $profileComplete ? 'bg-green-100' : 'bg-red-100' }} mr-3">
                                @if($profileComplete)
                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Profile Completion</h4>
                                <p class="text-sm text-gray-600">{{ $profileComplete ? 'Complete' : 'Please complete your profile information to unlock all roommate finding features.' }}</p>
                            </div>
                        </div>
                        @if(!$profileComplete)
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors">
                                Update Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($canAccessSystem)
<div class="flex gap-6">
    <!-- Left Sidebar Menu -->
    <div class="w-64 flex-shrink-0">
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🧭 Navigation</h3>
            <nav class="space-y-2">
                <a href="{{ route('roommates.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">Find Roommates</div>
                        <div class="text-xs text-gray-500">Browse compatible matches</div>
                    </div>
                </a>

                <a href="{{ route('matches.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">My Matches</div>
                        <div class="text-xs text-gray-500">View your matches</div>
                    </div>
                </a>

                <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">Messages</div>
                        <div class="text-xs text-gray-500">Chat with roommates</div>
                    </div>
                </a>

                <a href="{{ route('listings.create') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">Create Listing</div>
                        <div class="text-xs text-gray-500">Post your room</div>
                    </div>
                </a>

                <a href="{{ route('activity.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">Activity</div>
                        <div class="text-xs text-gray-500">Recent activity</div>
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 mb-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Welcome back, {{ $user->first_name }}! 🎉</h1>
                    <p class="text-indigo-100">Your profile is complete and all features are now unlocked. Start exploring roommate matches!</p>
                </div>
                <button onclick="openAIAssistant()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    AI Assistant
                </button>
            </div>
        </div>

        <!-- Dashboard Content Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Profile Views</span>
                        <span class="font-semibold text-blue-600">127</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">New Matches</span>
                        <span class="font-semibold text-green-600">8</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Messages</span>
                        <span class="font-semibold text-purple-600">23</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔥 Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-gray-600">New match with Sarah Chen</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                        <span class="text-gray-600">Profile viewed by 5 people</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-purple-400 rounded-full mr-2"></div>
                        <span class="text-gray-600">3 new messages received</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function openAIAssistant() {
    alert('AI Assistant feature coming soon! This will help you find compatible roommates based on your preferences.');
}
</script>

<!-- Profile Completion Celebration Notification -->
@if($canAccessSystem && session()->has('profile_just_completed'))
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 transform transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 4l4 4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">🎉 Profile Complete!</h3>
                <p class="text-sm text-gray-600 mb-6">Congratulations! Your profile is now complete and ready for roommate matching. You now have full access to all features!</p>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        ID Verification: {{ $isVerified ? 'Verified' : 'Complete' }}
                    </div>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Profile Information: Complete
                    </div>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        System Access: Unlocked
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('roommates.index') }}" @click="show = false" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors text-center">
                        Start Matching
                    </a>
                    <a href="{{ route('profile.show') }}" @click="show = false" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors text-center">
                        View Profile
                    </a>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Clear the session flag after showing notification
        setTimeout(() => {
            fetch('{{ route("profile.completion.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
        }, 1000);
    </script>
@endif

<script>
function openAIAssistant() {
    alert('AI Assistant feature coming soon! This will help you find compatible roommates based on your preferences.');
}
</script>
@endsection
