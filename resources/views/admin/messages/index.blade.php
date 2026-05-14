@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-8 mb-6 shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Message Conversations</h1>
                <p class="mt-2 text-sm font-semibold text-white/80">Monitor user-to-user communications</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                @if($unreadCount > 0)
                <form action="{{ route('admin.messages.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2.5 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-xl hover:bg-white/30 transition-all">
                        <i class="fas fa-envelope-open-text mr-2"></i>
                        Mark All Read
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.messages.create') }}" class="flex items-center px-4 py-2.5 bg-white text-indigo-600 text-sm font-semibold rounded-xl hover:bg-gray-100 transition-all shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    New Message
                </a>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-violet-400/20 rounded-full blur-2xl"></div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 mb-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Search conversations...">
            </div>
            <div class="w-full md:w-48">
                <select id="status-filter" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option value="">All Messages</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Conversations Grid -->
    <div class="grid gap-4">
        @forelse($messages as $conversation)
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition-all duration-300 {{ !$conversation->is_read ? 'ring-1 ring-indigo-500/20 bg-indigo-50/30 dark:bg-indigo-900/10' : '' }}">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Participants -->
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <!-- Avatar Stack -->
                        <div class="flex -space-x-2">
                            <!-- Sender -->
                            <div class="relative">
                                @if(!empty($conversation->sender?->avatar))
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700" 
                                         src="{{ route('avatar.serve', ['filename' => basename($conversation->sender->avatar)]) }}" 
                                         alt="{{ $conversation->sender?->name ?? 'Deleted User' }}"
                                         onerror="this.className='h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700'">
                                @elseif(!empty($conversation->sender?->profile_photo_path))
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700" 
                                         src="{{ route('profile.photo.serve', ['filename' => basename($conversation->sender->profile_photo_path)]) }}" 
                                         alt="{{ $conversation->sender?->name ?? 'Deleted User' }}"
                                         onerror="this.className='h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700'">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700">
                                        {{ strtoupper(substr($conversation->sender?->name ?? 'D', 0, 1)) }}
                                    </div>
                                @endif
                                @if(!$conversation->is_read)
                                    <span class="absolute -top-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-rose-500 border-2 border-white dark:border-gray-700"></span>
                                @endif
                            </div>
                            <!-- Receiver -->
                            <div class="relative">
                                @if(!empty($conversation->receiver?->avatar))
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700 bg-emerald-500" 
                                         src="{{ route('avatar.serve', ['filename' => basename($conversation->receiver->avatar)]) }}" 
                                         alt="{{ $conversation->receiver?->name ?? 'Deleted User' }}"
                                         onerror="this.className='h-12 w-12 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700'">
                                @elseif(!empty($conversation->receiver?->profile_photo_path))
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-700" 
                                         src="{{ route('profile.photo.serve', ['filename' => basename($conversation->receiver->profile_photo_path)]) }}" 
                                         alt="{{ $conversation->receiver?->name ?? 'Deleted User' }}"
                                         onerror="this.className='h-12 w-12 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700'">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm border-2 border-white dark:border-gray-700">
                                        {{ strtoupper(substr($conversation->receiver?->name ?? 'D', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Names & Info -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-gray-900 dark:text-white truncate">{{ $conversation->sender?->name ?? 'Deleted User' }}</span>
                                <i class="fas fa-arrow-right text-xs text-gray-400"></i>
                                <span class="font-semibold text-gray-900 dark:text-white truncate">{{ $conversation->receiver?->name ?? 'Deleted User' }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $conversation->total_count }} messages</span>
                                <span class="text-gray-300">|</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $conversation->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Message Preview -->
                <div class="lg:w-96 flex-shrink-0">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-3">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                @if(!empty($conversation->latest_message?->sender?->avatar))
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ route('avatar.serve', ['filename' => basename($conversation->latest_message->sender->avatar)]) }}" 
                                         alt=""
                                         onerror="this.className='h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs'">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs">
                                        {{ strtoupper(substr($conversation->latest_message?->sender?->name ?? 'D', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $conversation->subject ?? 'Message' }}
                                    @if($conversation->image)
                                        <i class="fas fa-image text-indigo-500 ml-1 text-xs"></i>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ Str::limit(strip_tags($conversation->body ?? $conversation->content ?? 'No message content'), 60) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status & Actions -->
                <div class="flex items-center gap-3 lg:justify-end">
                    @if($conversation->unread_count > 0)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                            {{ $conversation->unread_count }} new
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            Read
                        </span>
                    @endif
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.messages.show', $conversation->latest_message) }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                            <i class="fas fa-eye mr-2"></i>
                            View
                        </a>
                        <form action="{{ route('admin.messages.destroy', $conversation->latest_message) }}" method="POST" class="inline" onsubmit="return confirm('Delete this conversation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="h-20 w-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comment-slash text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No Conversations</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No user messages found in the system.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
    @endif
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
