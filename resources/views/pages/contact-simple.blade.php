@extends('layouts.guest')

@section('title', 'Contact Support')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Contact Support</h1>
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Get in Touch</h3>
                    <p class="text-gray-600 mb-6">We're here to help you with any questions.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <i class="fas fa-envelope text-3xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold text-gray-900 mb-2">Email Support</h4>
                        <p class="text-gray-700 mb-4">support@findmyroommate.com</p>
                        <p class="text-sm text-gray-600">Response within 24 hours</p>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <i class="fas fa-phone text-3xl text-green-600 mb-4"></i>
                        <h4 class="font-semibold text-gray-900 mb-2">Phone Support</h4>
                        <p class="text-gray-700 mb-4">+63 955-607-6938</p>
                        <p class="text-sm text-gray-600">Mon-Fri, 9AM-6PM PST</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-4">Office Location</h4>
                    <p class="text-gray-700 text-center">
                        <strong>Find My Roommate</strong><br>
                        Dagupan City, Pangasinan<br>
                        Philippines
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ url()->previous() ?: route('home') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                    ← Back to Previous Page
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
