@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">System Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your application settings and configurations</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Navigation -->
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Settings</h2>
                </div>
                <nav class="p-2">
                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md group">
                        <i class="fas fa-cog mr-3"></i>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.system') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-server mr-3"></i>
                        System Information
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-credit-card mr-3"></i>
                        Payment Settings
                    </a>
                </nav>
                <div class="p-4 border-t border-gray-200">
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-sync-alt mr-2"></i> Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">General Settings</h2>
                    
                    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Application Name -->
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700">Application Name</label>
                                <div class="mt-1">
                                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Application URL -->
                            <div>
                                <label for="app_url" class="block text-sm font-medium text-gray-700">Application URL</label>
                                <div class="mt-1">
                                    <input type="url" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Timezone -->
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                <div class="mt-1">
                                    <select id="timezone" name="timezone" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @foreach(timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}" {{ old('timezone', config('app.timezone')) === $timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Email From Address -->
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700">Email From Address</label>
                                <div class="mt-1">
                                    <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', config('mail.from.address')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Email From Name -->
                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700">Email From Name</label>
                                <div class="mt-1">
                                    <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', config('mail.from.name')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-5">
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.settings') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </a>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg" id="successMessage">
                    {{ session('success') }}
                </div>
                <script>
                    // Auto-hide success message after 5 seconds
                    setTimeout(() => {
                        document.getElementById('successMessage').remove();
                    }, 5000);
                </script>
            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg';
        toast.textContent = '{{ session('success') }}';
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
</script>
@endpush
@endif
@endsection
