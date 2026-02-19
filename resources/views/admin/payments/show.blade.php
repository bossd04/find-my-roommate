@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Payment Details</h1>
        <p class="mt-1 text-sm text-gray-600">View complete payment information</p>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <!-- Payment Status Badge -->
            <div class="mb-6">
                @if($payment->status === 'paid')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Paid
                    </span>
                @elseif($payment->isOverdue())
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-exclamation-circle mr-1"></i> Overdue
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i> Pending
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full object-cover" src="{{ $payment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->user->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $payment->user->name }}">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.payments.statement', $payment->user) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                <i class="fas fa-file-invoice mr-1"></i> View User Statement
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Payment ID:</span>
                            <span class="ml-2 text-sm text-gray-900">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Amount:</span>
                            <span class="ml-2 text-sm text-gray-900 font-semibold">₱{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Due Date:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $payment->due_date->format('F d, Y') }}</span>
                        </div>
                        @if($payment->payment_date)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Payment Date:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($payment->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Notes</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-sm text-gray-700">{{ $payment->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between">
                    <div>
                        @if($payment->status === 'pending')
                            <form action="{{ route('admin.payments.mark-paid', $payment) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Mark as Paid
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="space-x-3">
                        @if($payment->status === 'paid')
                            <a href="{{ route('admin.payments.receipt', $payment) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-receipt mr-2"></i>
                                Print Receipt
                            </a>
                        @endif
                        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
