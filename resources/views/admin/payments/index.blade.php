@extends('admin.layouts.app')

@section('title', 'Payment Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Payment Management</h1>
        <p class="mt-1 text-sm text-gray-600">Track and manage user payments</p>
    </div>

    <!-- Actions Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Add Payment
        </a>
        
        <form action="{{ route('admin.payments.generate-monthly') }}" method="POST" class="inline-flex">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-calendar-plus mr-2"></i>
                Generate Monthly Payments
            </button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $payment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->user->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $payment->user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                        <a href="{{ route('admin.payments.statement', $payment->user) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-file-invoice"></i> View Statement
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₱{{ number_format($payment->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->due_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'paid')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @elseif($payment->isOverdue())
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Overdue
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($payment->status === 'pending')
                                    <form action="{{ route('admin.payments.mark-paid', $payment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-check-circle"></i> Mark Paid
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-gray-600 hover:text-gray-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($payment->status === 'paid')
                                    <a href="{{ route('admin.payments.receipt', $payment) }}" target="_blank" class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-receipt"></i> Receipt
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No payment records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                
                @push('scripts')
@endpush
@endsection
