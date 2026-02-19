@extends('admin.layouts.app')

@section('title', 'Manage Listings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Listings Management</h1>
        <a href="{{ route('admin.listings.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add New Listing
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search listings...">
                    </div>
                </div>
                <div class="ml-4">
                    <select id="status-filter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Owner
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($listings as $listing)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($listing->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($listing->image) }}" alt="{{ $listing->title }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-home text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.listings.show', $listing) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ Str::limit($listing->title, 30) }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $listing->location }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $listing->landlord->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $listing->landlord->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₱{{ number_format($listing->price, 2) }}</div>
                            <div class="text-sm text-gray-500">per month</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($listing->is_available)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $listing->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.listings.edit', $listing) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.listings.destroy', $listing) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.listings.toggle-status', $listing) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gray-600 hover:text-gray-900 ml-2" title="{{ $listing->is_available ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $listing->is_available ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No listings found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($listings->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $listings->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('status-filter');
        let timer;

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            
            if (searchInput.value) {
                params.set('search', searchInput.value);
            } else {
                params.delete('search');
            }
            
            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            } else {
                params.delete('status');
            }
            
            // Reset pagination when filters change
            params.delete('page');
            
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Debounce search input
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(applyFilters, 500);
        });

        statusFilter.addEventListener('change', applyFilters);

        // Set initial values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        if (urlParams.has('status')) {
            statusFilter.value = urlParams.get('status');
        }
    });
</script>
@endpush
@endsection
