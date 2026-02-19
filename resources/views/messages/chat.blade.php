@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex h-[600px]">
            <!-- Sidebar -->
            <div class="w-1/3 border-r border-gray-200 bg-gray-50 overflow-y-auto">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Messages</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation->otherUser->id) }}" 
                           class="flex items-center p-4 hover:bg-gray-100 {{ $conversation->otherUser->id == $selectedUser?->id ? 'bg-blue-50' : '' }}">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" 
                                     src="{{ $conversation->otherUser->profile_photo_url }}" 
                                     alt="{{ $conversation->otherUser->name }}">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $conversation->otherUser->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate max-w-xs">
                                    {{ $conversation->latestMessage->content }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $conversation->latestMessage->created_at->diffForHumans() }}
                                    @if($conversation->unread_count > 0)
                                        <span class="ml-2 inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-500 text-white text-xs">
                                            {{ $conversation->unread_count }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                @if($selectedUser)
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $selectedUser->profile_photo_url }}" 
                                 alt="{{ $selectedUser->name }}">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $selectedUser->name }}</p>
                            <p class="text-xs text-gray-500">
                                @if($selectedUser->is_online)
                                    Online
                                @elseif($selectedUser->last_seen)
                                    Last seen {{ $selectedUser->last_seen->diffForHumans() }}
                                @else
                                    Offline
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800' }}">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <!-- Date header for new days -->
                                    @if ($loop->first || !$message->created_at->isSameDay($messages[$loop->index - 1]->created_at))
                                        <div class="w-full text-center my-2">
                                            <span class="inline-block px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">
                                                @if ($message->created_at->isToday())
                                                    Today
                                                @elseif ($message->created_at->isYesterday())
                                                    Yesterday
                                                @else
                                                    {{ $message->created_at->format('F j, Y') }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-end mt-1 space-x-1">
                                        <span class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-200' : 'text-gray-500' }}"
                                              title="{{ $message->created_at->format('M j, Y h:i A') }}">
                                            @if($message->created_at->isToday())
                                                {{ $message->created_at->format('h:i A') }}
                                            @elseif($message->created_at->isYesterday())
                                                Yesterday, {{ $message->created_at->format('h:i A') }}
                                            @else
                                                {{ $message->created_at->format('M j, h:i A') }}
                                            @endif
                                        </span>
                                        @if($message->sender_id === auth()->id())
                                            @if($message->read_at)
                                                <svg class="h-3 w-3 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 12.586l7.293-7.293a1 1 0 011.414 1.414l-8 8z" />
                                                </svg>
                                            @else
                                                <svg class="h-3 w-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200">
                        <form id="message-form" action="{{ route('messages.store', $selectedUser) }}" method="POST" class="flex space-x-2">
                            @csrf
                            <input type="text" 
                                   id="message-input"
                                   name="message" 
                                   placeholder="Type a message..." 
                                   class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                   autocomplete="off">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Send
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center p-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No conversation selected</h3>
                            <p class="mt-1 text-sm text-gray-500">Select a conversation or start a new one.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom of messages
    const messagesContainer = document.getElementById('messages');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Handle message form submission
    document.addEventListener('DOMContentLoaded', function() {
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const url = this.action;
                
                // Show the message immediately in the UI
                if (messageInput.value.trim() !== '') {
                    const tempId = 'msg-' + Date.now();
                    const currentUser = @json(auth()->user());
                    const messageHtml = `
                        <div id="${tempId}" class="flex justify-end">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-500 text-white">
                                <p class="text-sm">${messageInput.value}</p>
                                <div class="flex items-center justify-end mt-1 space-x-1">
                                    <span class="text-xs text-blue-200">
                                        Just now
                                    </span>
                                    <svg class="h-3 w-3 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 12.586l7.293-7.293a1 1 0 011.414 1.414l-8 8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    // Clear the input
                    const messageText = messageInput.value;
                    messageInput.value = '';
                    
                    // Send the message to the server
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ message: messageText })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Replace the temporary message with the one from the server
                            const tempMessage = document.getElementById(tempId);
                            if (tempMessage) {
                                const message = data.message;
                                const isSender = message.sender_id === {{ auth()->id() }};
                                const messageHtml = `
                                    <div class="flex ${isSender ? 'justify-end' : 'justify-start'}">
                                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isSender ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800'}">
                                            <p class="text-sm">${message.content}</p>
                                            <div class="flex items-center justify-end mt-1 space-x-1">
                                                <span class="text-xs ${isSender ? 'text-blue-200' : 'text-gray-500'}">
                                                    ${new Date(message.created_at).toLocaleTimeString('en-US', {hour: 'numeric', minute:'numeric', hour12: true})}
                                                </span>
                                                ${isSender ? `
                                                    <svg class="h-3 w-3 ${message.read_at ? 'text-blue-300' : 'text-gray-400'}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 12.586l7.293-7.293a1 1 0 011.414 1.414l-8 8z" />
                                                    </svg>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                                tempMessage.outerHTML = messageHtml;
                            }
                        } else {
                            console.error('Error sending message:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    });

    // Enable pusher for real-time updates
    window.Echo.private(`user.${@json(auth()->id())}`)
        .listen('.message.sent', (data) => {
            // Handle new message
            const messagesDiv = document.getElementById('messages');
            if (messagesDiv) {
                const message = data.message;
                const isSender = message.sender_id === @json(auth()->id());
                const messageHtml = `
                    <div class="flex ${isSender ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isSender ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800'}">
                            <p class="text-sm">${message.content}</p>
                            <div class="flex items-center justify-end mt-1 space-x-1">
                                <span class="text-xs ${isSender ? 'text-blue-200' : 'text-gray-500'}">
                                    ${new Date(message.created_at).toLocaleTimeString('en-US', {hour: 'numeric', minute:'numeric', hour12: true})}
                                </span>
                                ${isSender ? `
                                    <svg class="h-3 w-3 ${message.read_at ? 'text-blue-300' : 'text-gray-400'}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 12.586l7.293-7.293a1 1 0 011.414 1.414l-8 8z" />
                                    </svg>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                messagesDiv.insertAdjacentHTML('beforeend', messageHtml);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .listen('.message.read', (data) => {
            // Update read status of messages
            document.querySelectorAll('.message-status').forEach(el => {
                if (el.dataset.messageId === data.message_id.toString()) {
                    el.innerHTML = `
                        <svg class="h-3 w-3 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 12.586l7.293-7.293a1 1 0 011.414 1.414l-8 8z" />
                        </svg>
                    `;
                }
            });
        });
</script>
@endpush

@endsection
