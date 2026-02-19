@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Messages</h1>
        <div class="flex space-x-3">
            @if($unreadCount > 0)
            <form action="{{ route('admin.messages.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-envelope-open-text mr-2"></i> Mark All as Read
                </button>
            </form>
            @endif
            <a href="{{ route('admin.messages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i> New Message
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search messages...">
                    </div>
                </div>
                <div class="ml-4">
                    <select id="status-filter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Messages</option>
                        <option value="unread">Unread Only</option>
                        <option value="read">Read Only</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            From / To
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Message
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($messages as $message)
                    <tr class="{{ !$message->is_read ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $message->sender->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        to {{ $message->receiver->name ?? 'Unknown User' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium {{ !$message->is_read ? 'text-gray-900 font-semibold' : 'text-gray-700' }}">
                                {{ $message->subject }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                {{ Str::limit(strip_tags($message->body), 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$message->is_read)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Unread
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Read
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $message->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.messages.show', $message) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No messages found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($messages->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $messages->links() }}
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
