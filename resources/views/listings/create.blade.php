<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Listing') }}
        </h2>
    </x-slot>
    
    @if (session('success'))
    <div id="success-notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-y-0 opacity-100">
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="create-listing-form" action="{{ route('listings.store') }}" method="POST">
                        @csrf
                        
                        <!-- Listing Type -->
                        <div class="mb-6">
                            <label for="listing_type" class="block text-sm font-medium text-gray-700">
                                I am looking to
                            </label>
                            <select id="listing_type" name="listing_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="room">Rent out a room</option>
                                <option value="roommate">Find a roommate</option>
                                <option value="apartment">Find an apartment to share</option>
                            </select>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700">
                                Location
                            </label>
                            <input type="text" name="location" id="location" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Enter city or neighborhood">
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Price Range (per month)
                            </label>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_price" class="sr-only">Min</label>
                                    <input type="number" name="min_price" id="min_price" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Min">
                                </div>
                                <div>
                                    <label for="max_price" class="sr-only">Max</label>
                                    <input type="number" name="max_price" id="max_price" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Tell potential roommates about yourself, your living situation, and what you're looking for..."></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="button" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="submit" id="submit-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <span id="button-text">Create Listing</span>
                                <svg id="loading-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('create-listing-form');
                                const submitButton = document.getElementById('submit-button');
                                const buttonText = document.getElementById('button-text');
                                const loadingSpinner = document.getElementById('loading-spinner');
                                
                                if (form) {
                                    form.addEventListener('submit', function() {
                                        // Show loading state
                                        submitButton.disabled = true;
                                        buttonText.textContent = 'Creating...';
                                        loadingSpinner.classList.remove('hidden');
                                    });
                                }
                                
                                // Hide success notification after 5 seconds
                                const successNotification = document.getElementById('success-notification');
                                if (successNotification) {
                                    setTimeout(() => {
                                        successNotification.classList.add('translate-y-4', 'opacity-0');
                                        setTimeout(() => {
                                            successNotification.remove();
                                        }, 300);
                                    }, 5000);
                                }
                            });
                        </script>
                        @endpush
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
