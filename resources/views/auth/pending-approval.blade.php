@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Registration Pending Approval
            </h2>
            
            <p class="mt-2 text-sm text-gray-600">
                Your account has been created successfully and is now pending admin approval.
            </p>
        </div>
        
        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Account Created</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Your account has been successfully created with email: {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Pending Approval</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            An administrator will review your account shortly. You will receive an email once your account is approved.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">What's Next?</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Once approved, you'll be able to access the dashboard and all features of the platform.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-between">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Logout
                    </button>
                </form>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500">
                    Need help? <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-500">Contact Support</a>
                </p>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-500">
                You can check your approval status by logging back in later.
            </p>
        </div>
    </div>
</div>
@endsection
