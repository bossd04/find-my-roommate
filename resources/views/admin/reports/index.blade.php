@extends('admin.layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Reports</h1>
        <p class="mt-1 text-sm text-gray-600">View and generate system reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- User Registration Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">User Registrations</h3>
                        <p class="text-sm text-gray-500">Track new user signups over time</p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.reports.generate', ['type' => 'users']) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Generate Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Listings Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-home text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Listings</h3>
                        <p class="text-sm text-gray-500">View all property listings</p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.reports.generate', ['type' => 'listings']) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Generate Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-exchange-alt text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Transactions</h3>
                        <p class="text-sm text-gray-500">View payment transactions</p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.reports.generate', ['type' => 'transactions']) }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Results Section -->
<div class="mt-8">
    @if(isset($reportData) && !empty($reportData))
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ ucfirst($reportType) }} Report - {{ now()->format('F j, Y') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Generated on {{ now()->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
        <div class="border-t border-gray-200">
            @include('admin.reports.partials.' . $reportType)
        </div>
    </div>
    @endif
</div>

<!-- Export Options -->
@if(isset($reportData) && !empty($reportData))
<div class="mt-6 flex justify-end space-x-3">
    <a href="{{ route('admin.reports.export', ['type' => $reportType, 'format' => 'pdf']) }}" 
       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        <i class="fas fa-file-pdf mr-2"></i> Export as PDF
    </a>
    <a href="{{ route('admin.reports.export', ['type' => $reportType, 'format' => 'csv']) }}" 
       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        <i class="fas fa-file-csv mr-2"></i> Export as CSV
    </a>
    <a href="{{ route('admin.reports.export', ['type' => $reportType, 'format' => 'excel']) }}" 
       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <i class="fas fa-file-excel mr-2"></i> Export as Excel
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Add any JavaScript for reports here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any report-specific JavaScript
    });
</script>
@endpush
