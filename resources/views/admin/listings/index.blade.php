@extends('admin.layouts.app')

@section('title', 'Manage Listings')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Success Message -->
    @if (session('success'))
    <div id="success-notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-y-0 opacity-100 z-50">
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
    <div id="error-notification" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-y-0 opacity-100 z-50">
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Listings Management</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    All Room Listings <span class="text-indigo-500 dark:text-indigo-400 font-black ml-1">({{ $listings->total() ?? 0 }} total)</span>
                </p>
            </div>
            <a href="{{ route('admin.listings.create') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                <i class="fas fa-plus mr-3"></i> Add New Listing
            </a>
        </div>
    </div>

    <!-- Filters and Search Card -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Search -->
                <div class="flex-1 max-w-2xl">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" id="search" name="search" 
                               class="block w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400 dark:placeholder-gray-500" 
                               placeholder="Search listings..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="w-full lg:w-64">
                    <div class="relative">
                        <select id="status-filter" class="filter-dropdown w-full appearance-none px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 font-black text-sm text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="dropdown-arrow absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <button type="button" id="filter-btn" 
                            class="px-8 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-md shadow-indigo-500/20 active:scale-95 transition-all">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.listings.index') }}" 
                       class="p-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-indigo-600 rounded-2xl transition-colors">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Title</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Owner</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Price</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700">Created</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($listings as $listing)
                        <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-14 w-14 rounded-2xl bg-gray-100 dark:bg-gray-900 overflow-hidden shadow-inner border border-gray-200/50 dark:border-gray-700">
                                        @if($listing->images && $listing->images->first())
                                            <img src="{{ route('listing.image.serve', ['filename' => basename($listing->images->first()->path)]) }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center">
                                                <i class="fas fa-home text-gray-300 dark:text-gray-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-5 flex-1">
                                        <h3 class="text-sm font-black text-gray-900 dark:text-gray-50 leading-tight truncate max-w-[200px]">{{ $listing->title }}</h3>
                                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase mt-1">{{ $listing->property_type }} • {{ $listing->location }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50">{{ $listing->user->name ?? 'N/A' }}</div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $listing->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50">₱{{ number_format($listing->price, 2) }}</div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">per month</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($listing->status === 'pending' || (!$listing->is_active && $listing->status !== 'inactive'))
                                    <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
                                        <i class="fas fa-clock mr-1"></i> Pending Approval
                                    </span>
                                @elseif($listing->is_active)
                                    <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-900 dark:text-gray-50">{{ $listing->created_at->format('M j, Y') }}</div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $listing->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Debug: Status = {{ $listing->status ?? 'NULL' }}, is_active = {{ $listing->is_active ? 'true' : 'false' }} -->
                                    @if($listing->status === 'pending' || $listing->is_active == false)
                                        <form method="POST" action="{{ route('admin.listings.approve', $listing) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-emerald-500 text-white hover:bg-emerald-600 rounded-xl transition-colors font-bold text-xs" title="Approve Listing">
                                                <i class="fas fa-check mr-1"></i> APPROVE
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('listings.index') }}#listing-{{ $listing->id }}" 
                                       target="_blank"
                                       class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-xl transition-colors"
                                       title="View on User Site">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.listings.show', $listing) }}" class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.listings.edit', $listing) }}" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-400 font-bold uppercase tracking-widest">
                                No listings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle filter button click
    document.getElementById('filter-btn').addEventListener('click', function() {
        const status = document.getElementById('status-filter').value;
        const search = document.getElementById('search').value;
        
        // Build URL with query parameters
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (search) params.append('search', search);
        
        // Navigate to the filtered URL
        window.location.href = '{{ route('admin.listings.index') }}?' + params.toString();
    });

    // Handle Enter key in search input
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filter-btn').click();
        }
    });

    // Hide success notification after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successNotification = document.getElementById('success-notification');
        if (successNotification) {
            setTimeout(() => {
                successNotification.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => {
                    successNotification.remove();
                }, 300);
            }, 5000);
        }

        // Hide error notification after 5 seconds
        const errorNotification = document.getElementById('error-notification');
        if (errorNotification) {
            setTimeout(() => {
                errorNotification.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => {
                    errorNotification.remove();
                }, 300);
            }, 5000);
        }
    });
</script>
@endpush
@endsection
