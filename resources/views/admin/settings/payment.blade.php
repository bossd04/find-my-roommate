@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Payment Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Configure monthly payment tracking for users</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Navigation -->
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Settings</h2>
                </div>
                <nav class="p-2">
                    <a href="{{ route('admin.settings') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
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
                    <a href="{{ route('admin.settings.payment') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md group">
                        <i class="fas fa-credit-card mr-3"></i>
                        Payment Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('admin.settings.update-payment') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Monthly Payment Configuration -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Payment Configuration</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="monthly_amount" class="block text-sm font-medium text-gray-700">Monthly Payment Amount</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-700 font-semibold sm:text-sm">₱</span>
                                            </div>
                                            <input type="number" name="monthly_amount" id="monthly_amount" value="{{ config('payments.monthly_amount', 1000) }}" min="0" step="0.01" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Amount charged to users each month</p>
                                    </div>
                                    
                                    <div>
                                        <label for="due_date" class="block text-sm font-medium text-gray-700">Payment Due Date</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="date" name="due_date" id="due_date" value="{{ config('payments.due_date', now()->format('Y-m-d')) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-10 sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Select the specific date when payments are due (day and month)</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Accepted Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="manual" {{ config('payments.method', 'manual') === 'manual' ? 'selected' : '' }}>Manual Payment (Cash/Bank Transfer)</option>
                                            <option value="gcash" {{ config('payments.method', 'manual') === 'gcash' ? 'selected' : '' }}>GCash</option>
                                            <option value="bank_transfer" {{ config('payments.method', 'manual') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Primary payment method for monthly fees</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="payment_instructions" class="block text-sm font-medium text-gray-700">Payment Instructions</label>
                                        <textarea name="payment_instructions" id="payment_instructions" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter payment instructions for users...">{{ config('payments.instructions', 'Please pay your monthly fee at the office or via bank transfer.') }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Instructions shown to users when making payments</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Reminders -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Reminders</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="enable_reminders" name="enable_reminders" type="checkbox" {{ config('payments.enable_reminders', true) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="enable_reminders" class="font-medium text-gray-700">Enable Payment Reminders</label>
                                            <p class="text-gray-500">Send automatic email reminders to users about upcoming payments</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="reminder_days_before" class="block text-sm font-medium text-gray-700">Days Before Due Date</label>
                                            <input type="number" name="reminder_days_before" id="reminder_days_before" value="{{ config('payments.reminder_days_before', 3) }}" min="1" max="30" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-xs text-gray-500">Send reminder this many days before due date</p>
                                        </div>
                                        
                                        <div>
                                            <label for="overdue_days" class="block text-sm font-medium text-gray-700">Mark Overdue After</label>
                                            <input type="number" name="overdue_days" id="overdue_days" value="{{ config('payments.overdue_days', 7) }}" min="1" max="30" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-xs text-gray-500">Days after due date to mark as overdue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="pt-5">
                                <div class="flex justify-end">
                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
