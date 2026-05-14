@extends('layouts.app')

@section('content')
<div class="h-screen bg-gray-900 flex overflow-hidden">
    <!-- Left Sidebar Navigation -->
    <div class="w-64 bg-gray-800 border-r border-gray-700 flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">Find My Roommate</h1>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2">
            <p class="text-gray-400 text-xs uppercase font-semibold tracking-wider mb-4 px-2">Navigation</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium">Dashboard</span>
                    <p class="text-xs text-gray-500">Plan overview</p>
                </div>
            </a>

            <a href="{{ route('matches.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium">My Matches</span>
                    <p class="text-xs text-gray-500">View your matches</p>
                </div>
            </a>

            <a href="{{ route('roommates.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium">Browse Roommates</span>
                    <p class="text-xs text-gray-500">Browse compatible matches</p>
                </div>
            </a>

            <a href="{{ route('messages.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-indigo-600 text-white transition-colors">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium">Messages</span>
                    <p class="text-xs text-indigo-200">Chat with roommates</p>
                </div>
            </a>

            <a href="{{ route('activity.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-medium">Activity</span>
                    <p class="text-xs text-gray-500">Recent activity</p>
                </div>
            </a>
        </nav>

        <!-- Theme Toggle -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center justify-between px-2">
                <span class="text-gray-400 text-sm">Theme</span>
                <button id="theme-toggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-indigo-600 transition-colors">
                    <span class="translate-x-6 inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex">
        <!-- Conversations List -->
        <div class="w-80 bg-gray-800 border-r border-gray-700 flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white font-bold text-lg flex items-center">
                    <span class="mr-2">💬</span> Messages
                </h2>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conversation)
                    @php 
                        $isMuted = in_array($conversation->user->id, $mutedUserIds ?? []);
                        $isSelected = $selectedUser && $selectedUser->id === $conversation->user->id;
                    @endphp
                    <a href="{{ route('messages.show', $conversation->user->id) }}" 
                       class="flex items-center p-4 hover:bg-gray-700 {{ $isSelected ? 'bg-gray-700 border-l-4 border-indigo-500' : '' }} transition-all duration-200">
                        <div class="relative flex-shrink-0">
                            @if($conversation->user->avatar_url || $conversation->user->profile_photo_url)
                                <img src="{{ $conversation->user->avatar_url ?? $conversation->user->profile_photo_url }}" 
                                     alt="{{ $conversation->user->fullName() }}"
                                     class="h-12 w-12 rounded-full object-cover {{ $isMuted ? 'opacity-50' : '' }}">
                            @else
                                <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-medium {{ $isMuted ? 'opacity-50' : '' }}">
                                    {{ strtoupper(substr($conversation->user->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->user->last_name, 0, 1)) }}
                                </div>
                            @endif
                            @if($conversation->unread_count > 0 && !$isMuted)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $conversation->unread_count }}
                                </span>
                            @endif
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-white truncate {{ $isMuted ? 'text-gray-500' : '' }}">
                                    {{ $conversation->user->first_name }} {{ $conversation->user->last_name }}
                                </p>
                                @if($conversation->last_message)
                                    <span class="text-xs text-gray-500">
                                        {{ $conversation->last_message->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 truncate">
                                {{ $isMuted ? '🔇 Muted' : Str::limit($conversation->last_message->content ?? 'No messages yet', 30) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No conversations yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-gray-900">
            @if($selectedUser)
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-700 bg-gray-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="relative">
                            @if($selectedUser->avatar_url || $selectedUser->profile_photo_url)
                                <img src="{{ $selectedUser->avatar_url ?? $selectedUser->profile_photo_url }}" 
                                     alt="{{ $selectedUser->fullName() }}"
                                     class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr($selectedUser->first_name, 0, 1)) }}{{ strtoupper(substr($selectedUser->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ $selectedUser->first_name }} {{ $selectedUser->last_name }}</p>
                            <p class="text-xs text-gray-400">
                                @if($selectedUser->profile && $selectedUser->profile->city)
                                    {{ $selectedUser->profile->city }}
                                @else
                                    Online
                                @endif
                            </p>
                        </div>
                    </div>
                    <button onclick="toggleMute()" id="mute-btn" class="text-gray-400 hover:text-white p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                </div>

                <!-- Messages -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-900 custom-scrollbar" style="scroll-behavior: smooth;">
                    <style>
                        .custom-scrollbar::-webkit-scrollbar {
                            width: 6px;
                        }
                        .custom-scrollbar::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        .custom-scrollbar::-webkit-scrollbar-thumb {
                            background: rgba(79, 70, 229, 0.4);
                            border-radius: 10px;
                        }
                        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                            background: rgba(79, 70, 229, 0.7);
                        }
                    </style>
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="{{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-200' }} rounded-lg px-4 py-2 shadow">
                                    <p class="text-sm">{{ $message->content }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    {{ $message->created_at->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-400 font-medium">No messages</p>
                                <p class="text-xs text-gray-500">Get started by sending a message!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Scroll to Bottom Button -->
                <button id="scroll-to-bottom-btn" class="hidden absolute bottom-24 right-8 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 z-10">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-700 bg-gray-800">
                    @if(auth()->user()->hasBlocked($selectedUser->id))
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500">You have blocked this user. Unblock to send messages.</p>
                        </div>
                    @elseif(auth()->user()->isBlockedBy($selectedUser->id))
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500">You cannot reply to this conversation.</p>
                        </div>
                    @else
                    <form id="message-form" class="flex items-center space-x-3" action="{{ route('messages.store', $selectedUser->id) }}" method="POST">
                    @csrf
                    <input type="text"
                           id="message-input"
                           name="message"
                           placeholder="Type a message..."
                           class="flex-1 bg-gray-700 text-white rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           required autocomplete="off">
                        <button type="submit" 
                                id="send-btn"
                                class="p-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            @else
                <!-- No Conversation Selected -->
                <div class="flex-1 flex items-center justify-center bg-gray-900">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-400">No conversation selected</h3>
                        <p class="mt-1 text-sm text-gray-500">Select a conversation or start a new one.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll to bottom of messages
const messagesContainer = document.getElementById('messages-container');
if (messagesContainer) {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Handle message form submission
const messageForm = document.getElementById('message-form');
const messageInput = document.getElementById('message-input');

@if($selectedUser)
if (messageForm) {
    messageForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const input = document.getElementById('message-input');
                    const message = input.value.trim();

                    if (!message) return;

                    // Clear input immediately for better UX
                    input.value = '';

                    // Send message via fetch
                    fetch('{{ route("messages.store", $selectedUser->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: 'message=' + encodeURIComponent(message)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to send message');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // Add message to chat immediately without page reload
                            addMessageToChat(data.message);
                            scrollToBottom();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to send message. Please try again.');
                    });
                });
}
@endif

function addMessageToChat(messageData) {
                    const container = document.getElementById('messages-container');
                    const isMe = messageData.sender_id === {{ auth()->id() }};

                    const messageHtml = `
                        <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="${isMe ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-200'} rounded-lg px-4 py-2 shadow">
                                    <p class="text-sm">${escapeHtml(messageData.content)}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 ${isMe ? 'text-right' : 'text-left'}">
                                    ${new Date(messageData.created_at).toLocaleTimeString([], {hour: 'numeric', minute: '2-digit'})}
                                </p>
                            </div>
                        </div>
                    `;

                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = messageHtml;
                    container.appendChild(tempDiv.firstElementChild);
                }

                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                function scrollToBottom() {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.scrollTo({
                            top: container.scrollHeight,
                            behavior: 'smooth'
                        });
                    }
                }

                // Scroll to bottom logic with button visibility
                const scrollToBottomBtn = document.getElementById('scroll-to-bottom-btn');
                const messagesContainer = document.getElementById('messages-container');
                
                if (messagesContainer) {
                    messagesContainer.addEventListener('scroll', function() {
                        if (messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight > 300) {
                            scrollToBottomBtn?.classList.remove('hidden');
                        } else {
                            scrollToBottomBtn?.classList.add('hidden');
                        }
                    });
                }

                scrollToBottomBtn?.addEventListener('click', scrollToBottom);

// Mute toggle function
async function toggleMute() {
    const url = "{{ $selectedUser ? route('messages.is-muted', $selectedUser) : '' }}";
    if (!url) return;
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.is_muted) {
            // Unmute
            await fetch("{{ $selectedUser ? route('messages.unmute', $selectedUser) : '' }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
        } else {
            // Mute
            await fetch("{{ $selectedUser ? route('messages.mute', $selectedUser) : '' }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
        }
        
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>
@endpush

@endsection
