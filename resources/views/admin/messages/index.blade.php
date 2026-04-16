@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-8 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-50 tracking-tight">Messages</h1>
                <p class="mt-2 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Global Communication Hub</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                @if($unreadCount > 0)
                <form action="{{ route('admin.messages.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="group flex items-center px-6 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm">
                        <i class="fas fa-envelope-open-text mr-3 group-hover:scale-110 transition-transform"></i>
                        Mark All Read
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.messages.create') }}" class="group flex items-center px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-paper-plane mr-3 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    New Message
                </a>
            </div>
        </div>
        
        <!-- Decorative Background Element -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-3xl p-6 mb-8 border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 dark:text-gray-500 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" id="search" class="block w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-bold text-gray-900 dark:text-gray-50 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-0 focus:border-indigo-500 transition-all" placeholder="Search messages by subject or sender...">
            </div>
            <div class="w-full lg:w-64">
                <select id="status-filter" class="block w-full px-4 py-4 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-bold text-gray-900 dark:text-gray-50 focus:ring-0 focus:border-indigo-500 transition-all">
                    <option value="">All Messages</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/30">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Participant</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Subject & Content</th>
                        <th class="px-8 py-6 text-center text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Timestamp</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($messages as $conversation)
                    <tr class="group transition-all duration-300 {{ !$conversation->is_read ? 'bg-indigo-50/30 dark:bg-indigo-900/20' : 'hover:bg-gray-50/50 dark:hover:bg-gray-900/20' }}">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative group-hover:scale-110 transition-transform duration-300">
                                    @if(!empty($conversation->sender->avatar))
                                        <img class="h-12 w-12 rounded-2xl object-cover border-2 border-white dark:border-gray-800 shadow-lg shadow-indigo-500/20" 
                                             src="{{ route('avatar.serve', ['filename' => basename($conversation->sender->avatar)]) }}" 
                                             alt="{{ $conversation->sender->name }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjQiIGN5PSIyNCIgcj0iMjQiIGZpbGw9IiM2MzY2ZjEiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2IDdBNCA0IDAgMTEtOCAwIDQgNCAwIDAxOCAwWk0xMiAxNEE3IDcgMCAwMC01IDIxaDIxYTcgNyAwIDAwLTctN1oiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo8L3N2Zz4='">
                                    @elseif(!empty($conversation->sender->profile_photo_path))
                                        <img class="h-12 w-12 rounded-2xl object-cover border-2 border-white dark:border-gray-800 shadow-lg shadow-indigo-500/20" 
                                             src="{{ route('profile.photo.serve', ['filename' => basename($conversation->sender->profile_photo_path)]) }}" 
                                             alt="{{ $conversation->sender->name }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjQiIGN5PSIyNCIgcj0iMjQiIGZpbGw9IiM2MzY2ZjEiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2IDdBNCA0IDAgMTEtOCAwIDQgNCAwIDAxOCAwWk0xMiAxNEE3IDcgMCAwMC01IDIxaDIxYTcgNyAwIDAwLTctN1oiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo8L3N2Zz4='">
                                    @else
                                        <div class="h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-500/20">
                                            {{ strtoupper(substr($conversation->sender->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    @if(!$conversation->is_read)
                                        <span class="absolute -top-1 -right-1 block h-3 w-3 rounded-full bg-indigo-600 ring-4 ring-white transition-all group-hover:animate-ping"></span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-black text-gray-900 dark:text-gray-50 leading-tight">{{ $conversation->sender->name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1 flex items-center gap-2">
                                        <i class="fas fa-exchange-alt text-xs"></i> {{ $conversation->receiver->name ?? 'System' }}
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-full text-[9px]">{{ $conversation->total_count }} msgs</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm font-black {{ !$conversation->is_read ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-900 dark:text-gray-50' }} leading-tight mb-1 truncate max-w-md">
                                {{ $conversation->subject }}
                                @if($conversation->image)
                                    <i class="fas fa-image text-indigo-500 ml-2" title="Has image attachment"></i>
                                @endif
                            </div>
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate max-w-sm">
                                {{ Str::limit(strip_tags($conversation->body), 80) }}
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($conversation->unread_count > 0)
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                                    {{ $conversation->unread_count }} Unread
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    Read
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="text-xs font-black text-gray-900 dark:text-white leading-tight">
                                {{ $conversation->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                {{ $conversation->created_at->format('h:i A') }} ({{ $conversation->created_at->diffForHumans() }})
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('admin.messages.show', $conversation->latest_message) }}" class="p-3 bg-white dark:bg-gray-800 rounded-xl text-indigo-600 hover:bg-indigo-600 hover:text-white border-2 border-gray-100 dark:border-gray-700 hover:border-indigo-600 transition-all duration-300 shadow-sm" title="View Conversation">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <form action="{{ route('admin.messages.destroy', $conversation->latest_message) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this conversation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 bg-white dark:bg-gray-800 rounded-xl text-rose-500 hover:bg-rose-500 hover:text-white border-2 border-gray-100 dark:border-gray-700 hover:border-rose-500 transition-all duration-300 shadow-sm" title="Delete Conversation">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-20 w-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-comment-slash text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-none">Silent Communication</h3>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">No messages have been intercepted yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($messages->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
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
            
            if (searchInput.value.trim()) {
                params.set('search', searchInput.value.trim());
            } else {
                params.delete('search');
            }
            
            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            } else {
                params.delete('status');
            }
            
            params.delete('page');
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(applyFilters, 800);
        });

        statusFilter.addEventListener('change', applyFilters);
    });
</script>
@endpush
@endsection
