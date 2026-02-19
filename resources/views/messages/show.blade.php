@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .chat-container {
        height: calc(100vh - 150px);
    }
    .messages-container {
        height: calc(100% - 70px);
    }
    .message-bubble {
        max-width: 70%;
        word-wrap: break-word;
    }
    .message-bubble.sent {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-left: auto;
    }
    .message-bubble.received {
        background: #f3f4f6;
        color: #1f2937;
        margin-right: auto;
    }
    .message-status {
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .message-status.sent {
        color: #9ca3af;
    }
    .message-status.delivered {
        color: #3b82f6;
    }
    .message-status.read {
        color: #10b981;
    }
    .typing-indicator:after {
        content: '...';
        animation: typing 1.5s infinite;
        display: inline-block;
        overflow: hidden;
        vertical-align: bottom;
    }
    @keyframes typing {
        0% { width: 0.5em; }
        50% { width: 1em; }
        100% { width: 1.5em; }
    }
</style>
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-6 px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="flex h-screen-80">
                <!-- Sidebar with conversations -->
                <div class="w-1/3 border-r border-gray-200 bg-white flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-indigo-600 text-white">
                        <h2 class="text-lg font-semibold">Messages</h2>
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        @foreach($conversations as $conversation)
                            <a href="{{ route('messages.show', $conversation->user) }}" 
                               class="flex items-center p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 {{ $receiver->id === $conversation->user->id ? 'bg-blue-50' : '' }}">
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                        {{ strtoupper(substr($conversation->user->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->user->last_name, 0, 1)) }}
                                    </div>
                                    @if($conversation->user->isOnline())
                                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-500 border-2 border-white"></span>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            {{ $conversation->user->first_name }} {{ $conversation->user->last_name }}
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            @if($conversation->last_message)
                                                @if($conversation->last_message->created_at->isToday())
                                                    {{ $conversation->last_message->created_at->format('g:i A') }}
                                                @elseif($conversation->last_message->created_at->isYesterday())
                                                    Yesterday {{ $conversation->last_message->created_at->format('g:i A') }}
                                                @else
                                                    {{ $conversation->last_message->created_at->format('M j, g:i A') }}
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-500 truncate">
                                            @if($conversation->last_message)
                                                @if($conversation->last_message->sender_id === auth()->id())
                                                    You: {{ Str::limit($conversation->last_message->content, 20) }}
                                                @else
                                                    {{ Str::limit($conversation->last_message->content, 20) }}
                                                @endif
                                            @else
                                                No messages yet
                                            @endif
                                        </p>
                                        @if($conversation->unread_count > 0)
                                            <span class="bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                    <!-- Main chat area -->
                    <div class="flex-1 flex flex-col">
                        <!-- Chat header -->
                        <div class="p-3 border-b border-gray-200 bg-white flex items-center">
                            <div class="flex-shrink-0 md:hidden mr-2">
                                <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-medium">
                                    {{ strtoupper(substr($receiver->first_name, 0, 1)) }}{{ strtoupper(substr($receiver->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-2">
                                <h3 class="text-sm font-medium text-gray-900">
                                    {{ $receiver->first_name }} {{ $receiver->last_name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    @if($receiver->profile && $receiver->profile->location)
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $receiver->profile->location }}
                                        </span>
                                    @else
                                        <span class="text-yellow-600 text-xs">Location not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto flex space-x-2 relative" style="position: relative;">
                                <!-- Dropdown menu button -->
                                <button type="button" id="chat-menu-button" class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 relative" title="More options">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div id="chat-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200" style="z-index: 9999;">
                                    <div class="py-1">
                                        <!-- View Profile -->
                                        <a href="{{ route('profile.show', $receiver) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            View Profile
                                        </a>
                                        
                                        <!-- Search Messages -->
                                        <button type="button" id="search-messages-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Search Messages
                                        </button>
                                        
                                        <!-- Clear Chat -->
                                        <button type="button" id="clear-chat-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Clear Chat
                                        </button>
                                        
                                        <!-- Mute Notifications -->
                                        <button type="button" id="mute-notifications-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                            </svg>
                                            Mute Notifications
                                        </button>
                                        
                                        <!-- Call User -->
                                        <button type="button" id="call-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Call User
                                        </button>
                                        
                                        <!-- Block User -->
                                        <button type="button" id="block-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            Block User
                                        </button>
                                        
                                        <!-- Restrict User -->
                                        <button type="button" id="restrict-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-orange-600 hover:bg-orange-50 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Restrict User
                                        </button>
                                        
                                        <!-- Report User -->
                                        <button type="button" id="report-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Report User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages">
                        @forelse($messages as $message)
                            <div class="message-item {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} flex relative group">
                                @if($message->sender_id !== auth()->id())
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden mr-3">
                                        @if($message->sender->avatar && Storage::exists($message->sender->avatar))
                                            <img src="{{ Storage::url($message->sender->avatar) }}?{{ time() }}" 
                                                 alt="{{ $message->sender->fullName() }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-medium">
                                                {{ strtoupper(substr($message->sender->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }} rounded-2xl px-4 py-2 relative">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    
                                    <div class="flex items-center justify-end mt-1 space-x-1">
                                        <span class="text-xs {{ $message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500' }}">
                                            @if($message->created_at->isToday())
                                                {{ $message->created_at->format('g:i A') }}
                                            @elseif($message->created_at->isYesterday())
                                                Yesterday {{ $message->created_at->format('g:i A') }}
                                            @else
                                                {{ $message->created_at->format('M j, g:i A') }}
                                            @endif
                                        </span>
                                        @if($message->sender_id === auth()->id())
                                            <span class="text-xs message-status {{ $message->delivery_status }}">
                                                @if($message->delivery_status === 'read')
                                                    <i class="fas fa-check-double"></i>
                                                @elseif($message->delivery_status === 'delivered')
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    <i class="fas fa-clock"></i>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex items-center justify-center">
                                <div class="text-center">
                                    <div class="mx-auto h-16 w-16 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No messages</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by sending a message!</p>
                                </div>
                            </div>
                        @endforelse
                        <div id="typing-indicator" class="hidden items-center space-x-2 p-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            <span class="text-xs text-gray-500 ml-1">typing...</span>
                        </div>
                    </div>

                    <!-- Message input -->
                    <div class="border-t border-gray-200 p-4 bg-white">
                        <form id="message-form" action="{{ route('messages.store', $receiver) }}" method="POST" class="relative">
                            @csrf
                            <div class="flex items-center">
                                <div class="flex-1 mx-2">
                                    <input type="text" name="message" id="message-input" 
                                           class="w-full border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                           placeholder="Type a message..." autocomplete="off"
                                           onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); document.getElementById('send-button').click(); }">
                                </div>
                                <button type="submit" id="send-button" class="p-2 text-indigo-600 hover:text-indigo-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    // Initialize Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    // Subscribe to the private channel for the current user
    const channel = pusher.subscribe('private-chat.{{ auth()->id() }}');

    // Listen for new messages
    channel.bind('message.sent', function(data) {
        const message = data.message;
        const isCurrentUser = message.sender_id === {{ auth()->id() }};
        
        // Skip if this is the current user's message (already displayed immediately)
        if (isCurrentUser) return;
        
        const messageHtml = `
            <div class="message-item ${isCurrentUser ? 'justify-end' : 'justify-start'} flex relative group">
                ${!isCurrentUser ? `
                <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden mr-3">
                    ${message.sender.avatar ? `
                        <img src="${message.sender.avatar_url || '/storage/' + message.sender.avatar}" 
                             alt="${message.sender.first_name}" 
                             class="h-full w-full object-cover">
                    ` : `
                        <div class="h-full w-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-medium">
                            ${message.sender.first_name.charAt(0).toUpperCase()}
                        </div>
                    `}
                </div>` : ''}
                <div class="message-bubble ${isCurrentUser ? 'sent' : 'received'} rounded-2xl px-4 py-2 relative">
                    <p class="text-sm">${message.content}</p>
                    <div class="flex items-center justify-end mt-1 space-x-1">
                        <span class="text-xs ${isCurrentUser ? 'text-indigo-200' : 'text-gray-500'}">
                            ${(() => {
                                const messageDate = new Date(message.created_at);
                                const today = new Date();
                                const yesterday = new Date(today);
                                yesterday.setDate(yesterday.getDate() - 1);
                                
                                if (messageDate.toDateString() === today.toDateString()) {
                                    return messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                                } else if (messageDate.toDateString() === yesterday.toDateString()) {
                                    return 'Yesterday ' + messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                                } else {
                                    return messageDate.toLocaleDateString([], {month: 'short', day: 'numeric'}) + ', ' + messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                                }
                            })()}
                        </span>
                        ${isCurrentUser ? `
                        <span class="text-xs message-status ${message.delivery_status}">
                            ${message.delivery_status === 'read' ? '<i class="fas fa-check-double"></i>' : 
                              message.delivery_status === 'delivered' ? '<i class="fas fa-check"></i>' : 
                              '<i class="fas fa-clock"></i>'}
                        </span>` : ''}
                    </div>
                </div>
            </div>
        `;
        
        // If there's a "no messages" placeholder, remove it
        const noMessages = document.querySelector('.text-center.text-gray-500');
        if (noMessages) noMessages.remove();
        
        // Append new message
        document.getElementById('messages').insertAdjacentHTML('beforeend', messageHtml);
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });

    // Auto-scroll to bottom of messages
    const messagesContainer = document.getElementById('messages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Handle message submission with AJAX
    document.getElementById('message-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        try {
            const response = await fetch('{{ route('messages.store', $receiver) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: message
                })
            });
            
            const data = await response.json();
            
            console.log('Response data:', data); // Debug log
            
            if (data.status === 'success') {
                // Clear input
                messageInput.value = '';
                // Focus back on input
                messageInput.focus();
                
                // Immediately add the sent message to the chat
                const sentMessage = data.message;
                console.log('Sent message:', sentMessage); // Debug log
                
                // Create message HTML with proper error handling
                const messageDate = new Date(sentMessage.created_at);
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                
                let timeString;
                if (messageDate.toDateString() === today.toDateString()) {
                    timeString = messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                } else if (messageDate.toDateString() === yesterday.toDateString()) {
                    timeString = 'Yesterday ' + messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                } else {
                    timeString = messageDate.toLocaleDateString([], {month: 'short', day: 'numeric'}) + ', ' + messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
                }
                
                const messageHtml = `
                    <div class="message-item justify-end flex relative group">
                        <div class="message-bubble sent rounded-2xl px-4 py-2 relative">
                            <p class="text-sm">${sentMessage.content || ''}</p>
                            <div class="flex items-center justify-end mt-1 space-x-1">
                                <span class="text-xs text-indigo-200">${timeString}</span>
                                <span class="text-xs message-status ${sentMessage.delivery_status || 'sent'}">
                                    <i class="fas fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                
                console.log('Message HTML:', messageHtml); // Debug log
                
                // If there's a "no messages" placeholder, remove it
                const noMessagesContainer = document.querySelector('.h-full.flex.items-center.justify-center');
                if (noMessagesContainer) {
                    noMessagesContainer.remove();
                }
                
                // Append the sent message
                const messagesContainer = document.getElementById('messages');
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                console.log('Message added successfully'); // Debug log
            } else {
                console.error('Error sending message:', data.message);
                console.error('Full error response:', data);
            }
        } catch (error) {
            console.error('Network error:', error);
            console.error('Error details:', error.message);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing chat menu...');
    
    // Chat menu dropdown functionality
    const chatMenuButton = document.getElementById('chat-menu-button');
    const chatMenuDropdown = document.getElementById('chat-menu-dropdown');
    
    console.log('Chat menu button found:', !!chatMenuButton);
    console.log('Chat menu dropdown found:', !!chatMenuDropdown);
    
    if (chatMenuButton && chatMenuDropdown) {
        // Add click listener with debugging
        chatMenuButton.addEventListener('click', function(e) {
            console.log('Chat menu button clicked! Event:', e);
            e.preventDefault();
            e.stopPropagation();
            
            const isHidden = chatMenuDropdown.classList.contains('hidden');
            console.log('Dropdown was hidden:', isHidden);
            
            if (isHidden) {
                chatMenuDropdown.classList.remove('hidden');
                console.log('Dropdown shown');
            } else {
                chatMenuDropdown.classList.add('hidden');
                console.log('Dropdown hidden');
            }
            
            console.log('Dropdown classes:', chatMenuDropdown.className);
            console.log('Dropdown styles:', window.getComputedStyle(chatMenuDropdown));
        });
        
        // Test button visibility
        console.log('Button visible:', window.getComputedStyle(chatMenuButton).display !== 'none');
        console.log('Button position:', chatMenuButton.getBoundingClientRect());
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!chatMenuButton.contains(e.target) && !chatMenuDropdown.contains(e.target)) {
                chatMenuDropdown.classList.add('hidden');
                console.log('Dropdown closed due to outside click');
            }
        });
        
        // Handle menu actions
        document.getElementById('search-messages-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            // Implement search functionality
            const searchQuery = prompt('Search messages:');
            if (searchQuery) {
                // TODO: Implement message search
                console.log('Searching for:', searchQuery);
            }
        });
        
        document.getElementById('clear-chat-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            if (confirm('Are you sure you want to clear all messages in this chat? This action cannot be undone.')) {
                // TODO: Implement clear chat functionality
                console.log('Clearing chat...');
                // You can make an AJAX call to clear the chat
                fetch('{{ route('messages.clear', $receiver) }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        });
        
        document.getElementById('mute-notifications-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            // TODO: Implement mute notifications functionality
            alert('Notifications muted for this conversation');
            console.log('Muting notifications...');
        });
        
        document.getElementById('block-user-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            if (confirm('Are you sure you want to block this user? You will no longer receive messages from them.')) {
                // TODO: Implement block user functionality
                console.log('Blocking user...');
                // You can make an AJAX call to block the user
                fetch('{{ route('users.block', $receiver) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        alert('User blocked successfully');
                        window.location.href = '{{ route('messages.index') }}';
                    }
                });
            }
        });
        
        document.getElementById('report-user-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            const reason = prompt('Please describe why you want to report this user:');
            if (reason) {
                // TODO: Implement report user functionality
                console.log('Reporting user for:', reason);
                // You can make an AJAX call to report the user
                fetch('{{ route('users.report', $receiver) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ reason: reason })
                }).then(response => {
                    if (response.ok) {
                        alert('User reported successfully. We will review your report.');
                    }
                });
            }
        });
        
        document.getElementById('call-user-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            
            // Check if user has phone number in profile
            const userPhone = '{{ $receiver->profile->phone ?? $receiver->phone ?? null }}';
            const userName = '{{ $receiver->first_name }} {{ $receiver->last_name }}';
            
            if (userPhone && userPhone !== 'null' && userPhone.trim() !== '') {
                if (confirm(`Do you want to call ${userName} at ${userPhone}?`)) {
                    // Initiate call - could use WebRTC or redirect to phone app
                    window.open(`tel:${userPhone}`, '_self');
                }
            } else {
                alert(`${userName} has not shared their phone number. You can message them instead to ask for their contact details.`);
            }
        });
        
        document.getElementById('restrict-user-btn').addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            
            const userName = '{{ $receiver->first_name }} {{ $receiver->last_name }}';
            const restrictionOptions = [
                'Hide their messages from my feed',
                'Limit their ability to see my profile',
                'Restrict from seeing my online status',
                'All of the above'
            ];
            
            const selectedOption = prompt(
                `Select restriction for ${userName}:\n` +
                `1. ${restrictionOptions[0]}\n` +
                `2. ${restrictionOptions[1]}\n` +
                `3. ${restrictionOptions[2]}\n` +
                `4. ${restrictionOptions[3]}\n\n` +
                `Enter option number (1-4):`
            );
            
            if (selectedOption && ['1', '2', '3', '4'].includes(selectedOption)) {
                const restriction = restrictionOptions[parseInt(selectedOption) - 1];
                
                if (confirm(`Are you sure you want to apply this restriction: "${restriction}"?`)) {
                    // Make AJAX call to apply restriction
                    fetch('{{ route('users.restrict', $receiver) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            restriction_type: selectedOption,
                            restriction_description: restriction
                        })
                    }).then(response => {
                        if (response.ok) {
                            alert(`Restriction applied: ${restriction}`);
                        } else {
                            alert('Failed to apply restriction. Please try again.');
                        }
                    }).catch(error => {
                        console.error('Error applying restriction:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
            } else if (selectedOption) {
                alert('Invalid option selected.');
            }
        });
    } else {
        console.error('Chat menu elements not found!');
        console.error('Button element:', chatMenuButton);
        console.error('Dropdown element:', chatMenuDropdown);
    }
    });

    // Typing indicator
    let typingTimer;
    const messageInput = document.getElementById('message-input');
    
    messageInput.addEventListener('input', function() {
        // Show typing indicator
        const typingIndicator = document.getElementById('typing-indicator');
        typingIndicator.classList.remove('hidden');
        
        // Clear previous timer
        clearTimeout(typingTimer);
        
        // Hide typing indicator after 2 seconds of no typing
        typingTimer = setTimeout(() => {
            typingIndicator.classList.add('hidden');
        }, 2000);
    });
    
    // Mark messages as read when scrolled into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const messageId = entry.target.dataset.messageId;
                if (messageId) {
                    // Here you would typically make an API call to mark the message as read
                    console.log('Marking message as read:', messageId);
                }
            }
        });
    }, { threshold: 0.5 });
    
    // Observe all message elements
    document.querySelectorAll('.message-item').forEach(message => {
        observer.observe(message);
    });
</script>
@endpush
@endsection
