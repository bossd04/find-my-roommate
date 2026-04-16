@extends('layouts.app-with-sidebar')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .chat-container {
        height: calc(100vh - 150px);
    }
    .messages-container {
        height: calc(100% - 70px);
    }
    .chat-box {
        height: 350px;
        overflow-y: auto;  /* REQUIRED */
    }
    #messages {
        height: 350px;
        overflow-y: auto;   /* VERY IMPORTANT */
        display: flex;
        flex-direction: column;
    }
    .message-bubble {
        max-width: 70%;
        word-wrap: break-word;
    }
    .message-bubble.sent {
        background: #ffffff !important;
        color: #1f2937 !important;
        margin-left: auto;
        border: 2px solid #4f46e5;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .message-bubble.sent p,
    .message-bubble.sent * {
        color: #1f2937 !important;
    }
    .message-bubble.received {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white !important;
        margin-right: auto;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    }
    .message-bubble.received p,
    .message-bubble.received span,
    .message-bubble.received div,
    .message-bubble.received * {
        color: white !important;
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
<div class="bg-gray-900 min-h-screen">
    <div class="container mx-auto py-6 px-4">
        <div class="bg-gray-900 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            <div class="flex h-screen-80">
                <!-- Sidebar with conversations -->
                <div class="w-1/3 border-r border-gray-700 bg-gray-800 flex flex-col">
                    <div class="p-4 border-b border-gray-700 bg-gradient-to-r from-gray-900 to-black text-white">
                        <h2 class="text-lg font-semibold">Messages</h2>
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        @foreach($conversations as $conversation)
                            @php $isMuted = in_array($conversation->user->id, $mutedUserIds ?? []); @endphp
                            <a href="{{ route('messages.show', $conversation->user) }}" 
                               id="conversation-{{ $conversation->user->id }}"
                               data-user-id="{{ $conversation->user->id }}"
                               class="conversation-item flex items-center p-3 border-b border-gray-700 hover:bg-gray-700 transition-colors duration-200 {{ $receiver->id === $conversation->user->id ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-full overflow-hidden {{ $isMuted ? 'opacity-70' : '' }}">
                                        @if($conversation->user->avatar_url || $conversation->user->profile_photo_url)
                                            <img src="{{ $conversation->user->avatar_url ?? $conversation->user->profile_photo_url }}" 
                                                 alt="{{ $conversation->user->fullName() }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-700 flex items-center justify-center text-blue-400 font-semibold">
                                                {{ strtoupper(substr($conversation->user->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->user->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($conversation->user->isOnline())
                                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-500 border-2 border-white"></span>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-1.5">
                                            <h3 class="text-sm font-medium {{ $isMuted ? 'text-gray-400' : 'text-white' }}">
                                                {{ $conversation->user->first_name }} {{ $conversation->user->last_name }}
                                            </h3>
                                            @if(auth()->user()->hasBlocked($conversation->user->id))
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-600 text-white">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    Blocked
                                                </span>
                                            @elseif(auth()->user()->isBlockedBy($conversation->user->id))
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-600 text-white">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                    Can't reply
                                                </span>
                                            @elseif($isMuted)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-600 text-white" title="Notifications muted">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                                    </svg>
                                                    Muted
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">
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
                                    <div class="flex justify-between items-center mt-0.5">
                                        <p class="text-sm {{ $isMuted ? 'text-gray-500' : 'text-gray-400' }} truncate">
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
                                        @if($conversation->unread_count > 0 && !$isMuted)
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
                        <div class="p-3 border-b border-gray-700 bg-gray-800 flex items-center">
                            <div class="flex-shrink-0 md:hidden mr-2">
                                <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-gray-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full overflow-hidden">
                                    @if($receiver->avatar_url || $receiver->profile_photo_url)
                                        <img src="{{ $receiver->avatar_url ?? $receiver->profile_photo_url }}" 
                                             alt="{{ $receiver->fullName() }}" 
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gray-700 flex items-center justify-center text-blue-400 text-sm font-medium">
                                            {{ strtoupper(substr($receiver->first_name, 0, 1)) }}{{ strtoupper(substr($receiver->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="flex items-center">
                                    <h3 class="text-sm font-medium text-white">{{ $receiver->first_name }} {{ $receiver->last_name }}</h3>
                                    @if(auth()->user()->hasBlocked($receiver->id))
                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-600 text-white">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>Blocked</span>
                                    @elseif(auth()->user()->isBlockedBy($receiver->id))
                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-600 text-white">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>Can't reply</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-400">
                                    @if($receiver->profile && ($receiver->profile->location || $receiver->profile->city || $receiver->profile->apartment_location))
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $receiver->profile->location ?? $receiver->profile->city ?? $receiver->profile->apartment_location }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto flex space-x-2 relative" style="position: relative;">
                                <!-- Mute indicator (hidden by default, shown when muted) -->
                                <div id="mute-indicator" class="hidden p-2 rounded-full text-yellow-500" title="Notifications muted">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                </div>

                                <!-- Dropdown menu button -->
                                <button type="button" id="chat-menu-button" class="p-2 rounded-full text-gray-400 hover:text-gray-300 hover:bg-gray-700 relative" title="More options">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div id="chat-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-gray-800 rounded-lg shadow-lg border border-gray-700" style="z-index: 9999;">
                                    <div class="py-1">
                                        <!-- View Profile -->
                                        <a href="{{ route('profile.show', $receiver) }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            View Profile
                                        </a>
                                        
                                        <!-- Search Messages -->
                                        <button type="button" id="search-messages-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Search Messages
                                        </button>
                                        
                                        <!-- Clear Chat -->
                                        <button type="button" id="clear-chat-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Clear Chat
                                        </button>
                                        
                                        <!-- Mute Notifications -->
                                        <button type="button" id="mute-notifications-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                            </svg>
                                            Mute Notifications
                                        </button>
                                        
                                        <!-- Call User -->
                                        <button type="button" id="call-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Call User
                                        </button>
                                        
                                         <!-- Block/Unblock User -->
                                         @if(auth()->user()->hasBlocked($receiver->id))
                                         <button type="button" id="unblock-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-green-400 hover:bg-green-900 hover:bg-opacity-30 transition-colors text-left">
                                             <svg class="h-4 w-4 mr-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                             </svg>
                                             Unblock User
                                         </button>
                                         @else
                                         <button type="button" id="block-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900 hover:bg-opacity-30 transition-colors text-left">
                                             <svg class="h-4 w-4 mr-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                             </svg>
                                             Block User
                                         </button>
                                         @endif
                                        
                                        <!-- Restrict User -->
                                        <button type="button" id="restrict-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-orange-400 hover:bg-orange-900 hover:bg-opacity-30 transition-colors text-left">
                                            <svg class="h-4 w-4 mr-3 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Restrict User
                                        </button>
                                        
                                        <!-- Report User -->
                                        <button type="button" id="report-user-btn" class="flex items-center w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900 hover:bg-opacity-30 transition-colors text-left">
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
                    <div id="chat-box">
                        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages">
                        @forelse($messages as $message)
                            <div class="message-item {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} flex relative group">
                                @if($message->sender_id !== auth()->id())
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden mr-3">
                                        @if($message->sender->avatar_url || $message->sender->profile_photo_url)
                                            <img src="{{ $message->sender->avatar_url ?? $message->sender->profile_photo_url }}" 
                                                 alt="{{ $message->sender->fullName() }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-700 flex items-center justify-center text-blue-400 text-sm font-medium">
                                                {{ strtoupper(substr($message->sender->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }} rounded-2xl px-4 py-2 relative">
                                    <p class="text-sm text-white">{{ $message->content }}</p>
                                    
                                    <!-- Violation Warning -->
                                    @if(isset($message->metadata['violation']) && $message->metadata['violation'])
                                        <div class="mt-2 p-2 bg-red-900 bg-opacity-40 border border-red-500 rounded text-xs text-red-200">
                                            <div class="flex items-center mb-1">
                                                <svg class="h-3 w-3 mr-1 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <strong>Community Guideline Violation</strong>
                                            </div>
                                            {{ $message->metadata['warning'] ?? 'This message violates our community guidelines.' }}
                                        </div>
                                    @endif
                                    
                                    <!-- Attached Image -->
                                    @if($message->image)
                                    <div class="mt-2 mb-1">
                                        <a href="{{ route('message.image', ['filename' => basename($message->image)]) }}" target="_blank" class="block">
                                            <img src="{{ route('message.image', ['filename' => basename($message->image)]) }}" 
                                                 alt="Attached image" 
                                                 class="max-w-full max-h-48 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                        </a>
                                    </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-end mt-1 space-x-1">
                                        <span class="text-xs">
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
                                    <div class="mx-auto h-16 w-16 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-white">No messages</h3>
                                    <p class="mt-1 text-sm text-gray-400">Get started by sending a message!</p>
                                </div>
                            </div>
                        @endforelse
                        <div id="typing-indicator" class="hidden items-center space-x-2 p-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            <span class="text-xs text-gray-400 ml-1">typing...</span>
                        </div>
                    </div>
                    </div>

                    <!-- Message input -->
                    <div class="border-t border-gray-700 p-4 bg-gray-800">
                        @if(auth()->user()->hasBlocked($receiver->id))
                            <div class="text-center py-4 bg-gray-800 border-t border-gray-700">
                                <p class="text-gray-400 mb-2">You have blocked this user. Unblock to send a message.</p>
                                <button type="button" id="unblock-user-inline-btn" class="text-blue-500 hover:text-blue-400 font-medium">
                                    Unblock {{ $receiver->first_name }}
                                </button>
                            </div>
                        @elseif(auth()->user()->isBlockedBy($receiver->id))
                            <div class="text-center text-gray-400 py-4 bg-gray-800 border-t border-gray-700">
                                <p>You can't reply to this conversation.</p>
                            </div>
                        @else
                        <form id="message-form" action="{{ route('messages.store', $receiver) }}" method="POST" class="relative">
                            @csrf
                            <div class="flex items-center">
                                <div class="flex-1 mx-2">
                                    <input type="text" name="message" id="message-input" 
                                           class="w-full border border-gray-600 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-700 text-white placeholder-gray-400" 
                                           placeholder="Type a message..." autocomplete="off"
                                           onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); document.getElementById('send-button').click(); }">
                                </div>
                                <button type="submit" id="send-button" class="p-2 text-blue-500 hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call Modal -->
<div id="call-modal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-2xl p-6 max-w-4xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <div id="call-avatar" class="h-12 w-12 rounded-full bg-gray-700 flex items-center justify-center">
                    <span class="text-blue-400 font-medium text-lg">{{ strtoupper(substr($receiver->first_name, 0, 1)) }}{{ strtoupper(substr($receiver->last_name, 0, 1)) }}</span>
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-medium">{{ $receiver->fullName() }}</h3>
                    <p id="call-status" class="text-sm text-gray-400">Calling...</p>
                </div>
            </div>
            <button onclick="endCall()" class="p-2 rounded-full bg-red-600 hover:bg-red-700 text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.516l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z" />
                </svg>
            </button>
        </div>
        <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="height: 400px;">
            <video id="remote-video" autoplay playsinline class="w-full h-full object-cover"></video>
            <video id="local-video" autoplay playsinline muted class="absolute bottom-4 right-4 w-32 h-24 rounded-lg border-2 border-white object-cover bg-gray-800"></video>
        </div>
        <div class="flex justify-center items-center mt-4 space-x-4">
            <button id="mute-btn" onclick="toggleMute()" class="p-3 rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-all">
                <svg id="mute-icon-on" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
                <svg id="mute-icon-off" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                </svg>
            </button>
            <button id="video-btn" onclick="toggleVideo()" class="p-3 rounded-full bg-gray-700 hover:bg-gray-600 text-white transition-all">
                <svg id="video-icon-on" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <svg id="video-icon-off" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </button>
            <button onclick="endCall()" class="p-4 rounded-full bg-red-600 hover:bg-red-700 text-white transition-all">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.516l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Incoming Call Modal -->
<div id="incoming-call-modal" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="h-20 w-20 rounded-full bg-gray-700 flex items-center justify-center mx-auto mb-4">
            <span class="text-blue-400 font-medium text-2xl">{{ strtoupper(substr($receiver->first_name, 0, 1)) }}{{ strtoupper(substr($receiver->last_name, 0, 1)) }}</span>
        </div>
        <h3 class="text-white text-xl font-medium mb-2">{{ $receiver->fullName() }}</h3>
        <p id="incoming-call-type" class="text-gray-400 mb-6">Incoming call...</p>
        <div class="flex justify-center space-x-4">
            <button onclick="declineIncomingCall()" class="p-4 rounded-full bg-red-600 hover:bg-red-700 text-white">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.516l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z" />
                </svg>
            </button>
            <button onclick="acceptIncomingCall()" class="p-4 rounded-full bg-green-600 hover:bg-green-700 text-white">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // WebRTC and Call variables
    let localStream = null;
    let remoteStream = null;
    let peerConnection = null;
    let currentCallId = null;
    let isMuted = false;
    let isVideoOff = false;
    let callType = 'audio';
    let callPollingInterval = null;

    // Dropdown menu functionality
    const chatMenuButton = document.getElementById('chat-menu-button');
    const chatMenuDropdown = document.getElementById('chat-menu-dropdown');
    const receiverId = {{ $receiver->id }};

    if (chatMenuButton && chatMenuDropdown) {
        chatMenuButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle dropdown
            chatMenuDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!chatMenuButton.contains(e.target) && !chatMenuDropdown.contains(e.target)) {
                chatMenuDropdown.classList.add('hidden');
            }
        });

        // Search Messages
        document.getElementById('search-messages-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            showSearchModal();
        });

        // Clear Chat
        document.getElementById('clear-chat-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            if (confirm('Are you sure you want to clear all messages in this chat? This action cannot be undone.')) {
                clearChat();
            }
        });

        // Mute Notifications
        document.getElementById('mute-notifications-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            showMuteOptions();
        });

        // Call User
        document.getElementById('call-user-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            showCallModal();
        });

        // Block User
        document.getElementById('block-user-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            if (confirm('Are you sure you want to block this user? They will no longer be able to message you.')) {
                const reason = prompt('Optional: Enter a reason for blocking (or leave empty):');
                blockUser(reason);
            }
        });

        // Unblock User (from dropdown)
        document.getElementById('unblock-user-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            if (confirm('Are you sure you want to unblock this user?')) {
                unblockUser();
            }
        });

        // Unblock User (inline)
        document.getElementById('unblock-user-inline-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to unblock this user?')) {
                unblockUser();
            }
        });

        // Restrict User (show info since regular users can't restrict)
        document.getElementById('restrict-user-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            alert('The restrict feature is for admin use only. If you need to report this user, please use the "Report User" option.');
        });

        // Report User
        document.getElementById('report-user-btn')?.addEventListener('click', function() {
            chatMenuDropdown.classList.add('hidden');
            showReportModal();
        });
    }

    // Check mute status on page load
    checkMuteStatus();

    // Function to check if conversation is muted and update UI
    async function checkMuteStatus() {
        try {
            const response = await fetch(`/messages/${receiverId}/muted`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            updateMuteIndicator(data.is_muted);
        } catch (error) {
            console.error('Error checking mute status:', error);
        }
    }

    // Function to show/hide mute indicator and update sidebar
    function updateMuteIndicator(isMuted) {
        const muteIndicator = document.getElementById('mute-indicator');
        if (muteIndicator) {
            if (isMuted) {
                muteIndicator.classList.remove('hidden');
            } else {
                muteIndicator.classList.add('hidden');
            }
        }

        // Update sidebar conversation item
        const conversationItem = document.querySelector(`[data-user-id="${receiverId}"]`);
        if (conversationItem) {
            const avatar = conversationItem.querySelector('.rounded-full.overflow-hidden');
            const nameText = conversationItem.querySelector('h3');
            const nameContainer = conversationItem.querySelector('.flex.items-center.gap-1\\.5');
            const unreadBadge = conversationItem.querySelector('.absolute.-top-1.-right-1');

            if (isMuted) {
                // Dim avatar
                if (avatar) avatar.classList.add('opacity-70');
                // Dim name text
                if (nameText) nameText.classList.replace('text-white', 'text-gray-400');
                // Hide unread badge when muted
                if (unreadBadge) unreadBadge.classList.add('hidden');
                // Add muted badge next to name if not already present
                let mutedBadge = nameContainer?.querySelector('.bg-yellow-600');
                if (!mutedBadge && nameContainer) {
                    mutedBadge = document.createElement('span');
                    mutedBadge.className = 'inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-600 text-white';
                    mutedBadge.title = 'Notifications muted';
                    mutedBadge.innerHTML = `
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                        </svg>
                        Muted
                    `;
                    nameContainer.appendChild(mutedBadge);
                }
            } else {
                // Restore avatar opacity
                if (avatar) avatar.classList.remove('opacity-70');
                // Restore name text color
                if (nameText) nameText.classList.replace('text-gray-400', 'text-white');
                // Show unread badge if there are unread messages (we need to check via API or re-render)
                if (unreadBadge) unreadBadge.classList.remove('hidden');
                // Remove muted badge
                const mutedBadge = nameContainer?.querySelector('.bg-yellow-600');
                if (mutedBadge) mutedBadge.remove();
            }
        }
    }

    // Search Messages Functionality
    function showSearchModal() {
        const searchQuery = prompt('Enter search term to find messages:');
        if (searchQuery && searchQuery.trim()) {
            searchMessages(searchQuery.trim());
        }
    }

    async function searchMessages(query) {
        try {
            const response = await fetch(`/messages/${receiverId}/search?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.status === 'success') {
                displaySearchResults(data.results, data.count, query);
            } else {
                alert('Error searching messages: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Search error:', error);
            alert('Failed to search messages. Please try again.');
        }
    }

    function displaySearchResults(results, count, query) {
        if (count === 0) {
            alert(`No messages found matching "${query}"`);
            return;
        }

        let resultText = `Found ${count} message(s) matching "${query}":\n\n`;
        results.slice(0, 10).forEach((msg, index) => {
            const preview = msg.content.length > 50 ? msg.content.substring(0, 50) + '...' : msg.content;
            resultText += `${index + 1}. [${msg.formatted_time}] ${msg.sender_name}: ${preview}\n`;
        });

        if (count > 10) {
            resultText += `\n... and ${count - 10} more results`;
        }

        alert(resultText);
    }

    // Clear Chat Functionality
    async function clearChat() {
        try {
            const response = await fetch(`/messages/${receiverId}/clear`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.status === 'success') {
                // Clear messages from UI
                const messagesContainer = document.getElementById('messages');
                if (messagesContainer) {
                    messagesContainer.innerHTML = `
                        <div class="h-full flex items-center justify-center">
                            <div class="text-center">
                                <div class="mx-auto h-16 w-16 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-white">Chat Cleared</h3>
                                <p class="mt-1 text-sm text-gray-400">All messages have been deleted.</p>
                            </div>
                        </div>
                    `;
                }
                alert('Chat cleared successfully');
            } else {
                alert('Error clearing chat: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Clear chat error:', error);
            alert('Failed to clear chat. Please try again.');
        }
    }

    // Mute Notifications Functionality
    async function showMuteOptions() {
        // First check if already muted
        try {
            const response = await fetch(`/messages/${receiverId}/muted`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.is_muted) {
                // Already muted - offer to unmute
                if (confirm('Notifications are currently muted for this conversation. Would you like to unmute?')) {
                    unmuteConversation();
                }
                return;
            }

            // Not muted - show mute options
            const options = [
                { value: '1hour', label: '1 Hour' },
                { value: '8hours', label: '8 Hours' },
                { value: '24hours', label: '24 Hours' },
                { value: '1week', label: '1 Week' },
                { value: 'forever', label: 'Until I unmute' }
            ];

            let choice = prompt('Mute notifications for:\n\n1. 1 Hour\n2. 8 Hours\n3. 24 Hours\n4. 1 Week\n5. Until I unmute\n\nEnter number (1-5):');

            if (!choice) return;

            choice = parseInt(choice.trim());
            if (choice >= 1 && choice <= 5) {
                muteConversation(options[choice - 1].value);
            } else {
                alert('Invalid selection');
            }
        } catch (error) {
            console.error('Error checking mute status:', error);
        }
    }

    async function muteConversation(duration) {
        const url = `/messages/${receiverId}/mute`;
        console.log('Muting conversation - receiverId:', receiverId, 'URL:', url);
        console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ duration: duration })
            });

            console.log('Mute response status:', response.status, response.statusText);
            
            // Get response as text first for debugging
            const responseText = await response.text();
            console.log('Mute raw response:', responseText.substring(0, 500));
            
            // Try to parse as JSON
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                alert('Server returned invalid JSON. Status: ' + response.status + '. Check console for raw response.');
                return;
            }
            
            console.log('Mute parsed data:', data);

            if (data.status === 'success') {
                alert(data.message);
                updateMuteIndicator(true);
            } else if (data.message === 'Conversation is already muted') {
                alert('Conversation is already muted');
                updateMuteIndicator(true);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Mute error details:', error.name, error.message);
            console.error('Full error:', error);
            alert('Failed to mute notifications. Network error: ' + error.message + '. Check console (F12) for details.');
        }
    }

    // Unmute Notifications Functionality
    async function unmuteConversation() {
        const url = `/messages/${receiverId}/unmute`;
        console.log('Unmuting conversation - receiverId:', receiverId, 'URL:', url);
        console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            console.log('Unmute response status:', response.status, response.statusText);
            
            // Get response as text first for debugging
            const responseText = await response.text();
            console.log('Unmute raw response:', responseText.substring(0, 500));
            
            // Try to parse as JSON
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                alert('Server returned invalid JSON. Status: ' + response.status + '. Check console for raw response.');
                return;
            }
            
            console.log('Unmute parsed data:', data);

            if (data.status === 'success') {
                alert(data.message);
                updateMuteIndicator(false);
            } else if (data.message === 'Conversation is not muted') {
                alert('Conversation is not muted');
                updateMuteIndicator(false);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Unmute error details:', error.name, error.message);
            console.error('Full error:', error);
            alert('Failed to unmute notifications. Network error: ' + error.message + '. Check console (F12) for details.');
        }
    }

    // Call User Functionality
    function showCallModal() {
        const choice = prompt('Select call type:\n\n1. Video Call\n2. Voice Call\n3. Cancel\n\nEnter number:');

        if (choice === '1') {
            initiateCall('video');
        } else if (choice === '2') {
            initiateCall('audio');
        }
    }

    // WebRTC Configuration
    const iceServers = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ]
    };

    // Initiate a call
    async function initiateCall(type) {
        callType = type;

        try {
            // Get user media
            const constraints = type === 'video'
                ? { video: true, audio: true }
                : { video: false, audio: true };

            localStream = await navigator.mediaDevices.getUserMedia(constraints);

            // Show/hide video button based on call type
            const videoBtn = document.getElementById('video-btn');
            if (videoBtn) {
                if (type === 'audio') {
                    videoBtn.classList.add('hidden');
                } else {
                    videoBtn.classList.remove('hidden');
                }
            }

            // Reset icons
            updateMuteIcon(false);
            updateVideoIcon(false);

            // Show local video
            const localVideo = document.getElementById('local-video');
            localVideo.classList.remove('hidden');
            localVideo.srcObject = localStream;

            // Create peer connection
            peerConnection = new RTCPeerConnection(iceServers);

            // Add local stream tracks to peer connection
            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });

            // Handle remote stream
            peerConnection.ontrack = (event) => {
                const remoteVideo = document.getElementById('remote-video');
                remoteVideo.srcObject = event.streams[0];
                document.getElementById('call-status').textContent = 'Connected';

                // Hide avatar when video is connected
                if (callType === 'video') {
                    const callAvatar = document.getElementById('call-avatar');
                    if (callAvatar) callAvatar.classList.add('hidden');
                }
            };

            // Create offer
            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);

            // Initiate call with server
            const response = await fetch(`/calls/${receiverId}/initiate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    call_type: type,
                    offer_sdp: offer.sdp
                })
            });

            const data = await response.json();

            if (data.status === 'success') {
                currentCallId = data.call_id;
                document.getElementById('call-modal').classList.remove('hidden');
                document.getElementById('call-status').textContent = 'Calling...';

                // Start polling for answer
                startCallPolling();
            } else {
                // If there's already an active call, try to force end it and retry
                if (data.message && data.message.includes('already an active call')) {
                    if (confirm('There is a stuck active call. Would you like to clear it and try again?')) {
                        await forceEndAllCalls();
                        // Retry initiating the call
                        setTimeout(() => initiateCall(type), 500);
                    } else {
                        cleanupCall();
                    }
                } else {
                    alert(data.message || 'Failed to initiate call');
                    cleanupCall();
                }
            }
        } catch (error) {
            console.error('Call initiation error:', error);
            alert('Could not access camera/microphone. Please check your permissions.');
            cleanupCall();
        }
    }

    // Start polling for call status
    function startCallPolling() {
        callPollingInterval = setInterval(async () => {
            if (!currentCallId) return;

            try {
                const response = await fetch(`/calls/${currentCallId}/status`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.status === 'success') {
                    if (data.call_status === 'connected' && data.answer_sdp) {
                        // Set remote description
                        const answer = new RTCSessionDescription({
                            type: 'answer',
                            sdp: data.answer_sdp
                        });
                        await peerConnection.setRemoteDescription(answer);
                        document.getElementById('call-status').textContent = 'Connected';
                        clearInterval(callPollingInterval);
                    } else if (data.call_status === 'declined') {
                        alert('Call declined');
                        endCall();
                    } else if (data.call_status === 'ended') {
                        endCall();
                    }

                    // Handle ICE candidates
                    if (data.ice_candidate) {
                        const candidate = new RTCIceCandidate(JSON.parse(data.ice_candidate));
                        await peerConnection.addIceCandidate(candidate);
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }, 2000);
    }

    // Update mute button icon
    function updateMuteIcon(muted) {
        const muteIconOn = document.getElementById('mute-icon-on');
        const muteIconOff = document.getElementById('mute-icon-off');
        const muteBtn = document.getElementById('mute-btn');

        if (muted) {
            muteIconOn?.classList.add('hidden');
            muteIconOff?.classList.remove('hidden');
            muteBtn?.classList.add('bg-red-600');
            muteBtn?.classList.remove('bg-gray-700');
        } else {
            muteIconOn?.classList.remove('hidden');
            muteIconOff?.classList.add('hidden');
            muteBtn?.classList.remove('bg-red-600');
            muteBtn?.classList.add('bg-gray-700');
        }
    }

    // Update video button icon
    function updateVideoIcon(videoOff) {
        const videoIconOn = document.getElementById('video-icon-on');
        const videoIconOff = document.getElementById('video-icon-off');
        const videoBtn = document.getElementById('video-btn');

        if (videoOff) {
            videoIconOn?.classList.add('hidden');
            videoIconOff?.classList.remove('hidden');
            videoBtn?.classList.add('bg-red-600');
            videoBtn?.classList.remove('bg-gray-700');
        } else {
            videoIconOn?.classList.remove('hidden');
            videoIconOff?.classList.add('hidden');
            videoBtn?.classList.remove('bg-red-600');
            videoBtn?.classList.add('bg-gray-700');
        }
    }

    // Toggle mute
    function toggleMute() {
        if (localStream) {
            const audioTrack = localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                isMuted = !audioTrack.enabled;
                updateMuteIcon(isMuted);
            }
        }
    }

    // Toggle video
    function toggleVideo() {
        if (localStream) {
            const videoTrack = localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                isVideoOff = !videoTrack.enabled;
                updateVideoIcon(isVideoOff);

                // Hide/show local video preview
                const localVideo = document.getElementById('local-video');
                if (isVideoOff) {
                    localVideo.classList.add('hidden');
                } else {
                    localVideo.classList.remove('hidden');
                }
            }
        }
    }

    // End call
    async function endCall() {
        if (callPollingInterval) {
            clearInterval(callPollingInterval);
            callPollingInterval = null;
        }

        if (currentCallId) {
            try {
                const response = await fetch(`/calls/${currentCallId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();
                console.log('End call response:', data);

                if (!response.ok) {
                    console.error('Failed to end call:', data);
                }
            } catch (error) {
                console.error('Error ending call:', error);
            }
        }

        cleanupCall();
        document.getElementById('call-modal').classList.add('hidden');
        document.getElementById('incoming-call-modal').classList.add('hidden');
    }

    // Cleanup call resources
    function cleanupCall() {
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
            localStream = null;
        }

        if (peerConnection) {
            peerConnection.close();
            peerConnection = null;
        }

        currentCallId = null;

        const localVideo = document.getElementById('local-video');
        const remoteVideo = document.getElementById('remote-video');
        if (localVideo) localVideo.srcObject = null;
        if (remoteVideo) remoteVideo.srcObject = null;

        // Show avatar again when call ends
        const callAvatar = document.getElementById('call-avatar');
        if (callAvatar) callAvatar.classList.remove('hidden');
    }

    // Force end all active calls (for clearing stuck calls)
    async function forceEndAllCalls() {
        try {
            const response = await fetch('/calls/force-end-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            console.log('Force end all calls response:', data);

            if (data.status === 'success') {
                console.log(`Cleared ${data.count} stuck calls`);
            } else {
                console.error('Failed to clear calls:', data.message);
            }
        } catch (error) {
            console.error('Error force ending calls:', error);
        }
    }

    // Incoming call timeout
    let incomingCallTimeout = null;

    // Check for incoming calls periodically
    setInterval(async () => {
        try {
            const response = await fetch('/calls/incoming', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.has_incoming && !currentCallId) {
                // Show incoming call modal
                currentCallId = data.call.id;
                callType = data.call.call_type;
                document.getElementById('incoming-call-type').textContent =
                    `Incoming ${callType} call...`;
                document.getElementById('incoming-call-modal').classList.remove('hidden');

                // Set timeout for missed call (30 seconds)
                incomingCallTimeout = setTimeout(async () => {
                    if (currentCallId === data.call.id) {
                        // Auto-decline after timeout
                        await fetch(`/calls/${currentCallId}/decline`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                        });
                        document.getElementById('incoming-call-modal').classList.add('hidden');
                        currentCallId = null;
                    }
                }, 30000);
            }
        } catch (error) {
            console.error('Error checking incoming calls:', error);
        }
    }, 5000);

    // Accept incoming call
    async function acceptIncomingCall() {
        // Clear the timeout
        if (incomingCallTimeout) {
            clearTimeout(incomingCallTimeout);
            incomingCallTimeout = null;
        }

        try {
            // Get user media
            const constraints = callType === 'video'
                ? { video: true, audio: true }
                : { video: false, audio: true };

            localStream = await navigator.mediaDevices.getUserMedia(constraints);

            const localVideo = document.getElementById('local-video');
            localVideo.srcObject = localStream;

            // Create peer connection
            peerConnection = new RTCPeerConnection(iceServers);

            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });

            peerConnection.ontrack = (event) => {
                const remoteVideo = document.getElementById('remote-video');
                remoteVideo.srcObject = event.streams[0];
            };

            // Get call details and set remote description
            const callResponse = await fetch(`/calls/${currentCallId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const callData = await callResponse.json();

            if (callData.status === 'success' && callData.call.offer_sdp) {
                const offer = new RTCSessionDescription({
                    type: 'offer',
                    sdp: callData.call.offer_sdp
                });
                await peerConnection.setRemoteDescription(offer);

                // Create answer
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);

                // Accept call with answer
                await fetch(`/calls/${currentCallId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ answer_sdp: answer.sdp })
                });

                document.getElementById('incoming-call-modal').classList.add('hidden');
                document.getElementById('call-modal').classList.remove('hidden');
                document.getElementById('call-status').textContent = 'Connected';

                // Start polling for ICE candidates and call status
                startCallPolling();
            }
        } catch (error) {
            console.error('Error accepting call:', error);
            alert('Could not access camera/microphone');
            declineIncomingCall();
        }
    }

    // Decline incoming call
    async function declineIncomingCall() {
        // Clear the timeout
        if (incomingCallTimeout) {
            clearTimeout(incomingCallTimeout);
            incomingCallTimeout = null;
        }

        if (currentCallId) {
            try {
                await fetch(`/calls/${currentCallId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
            } catch (error) {
                console.error('Error declining call:', error);
            }
        }

        currentCallId = null;
        document.getElementById('incoming-call-modal').classList.add('hidden');
    }

    // Block User Functionality
    async function blockUser(reason) {
        try {
            const response = await fetch(`/users/${receiverId}/block`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reason: reason })
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert('User blocked successfully. You will no longer receive messages from this user.');
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Block error:', error);
            alert('Failed to block user. Please try again.');
        }
    }

    // Unblock User Functionality
    async function unblockUser() {
        try {
            const response = await fetch(`/users/${receiverId}/unblock`, {
                method: 'POST', // Changed from DELETE to match Route::post if intended, but web.php says post
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert('User unblocked successfully.');
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Unblock error:', error);
            alert('Failed to unblock user. Please try again.');
        }
    }

    // Report User Functionality
    function showReportModal() {
        const reportTypes = [
            { value: 'inappropriate_behavior', label: 'Inappropriate Behavior' },
            { value: 'harassment', label: 'Harassment' },
            { value: 'spam', label: 'Spam' },
            { value: 'fake_profile', label: 'Fake Profile' },
            { value: 'scam', label: 'Scam/Fraud' },
            { value: 'other', label: 'Other' }
        ];

        let typeChoice = prompt('Report Type:\n\n1. Inappropriate Behavior\n2. Harassment\n3. Spam\n4. Fake Profile\n5. Scam/Fraud\n6. Other\n\nEnter number (1-6):');

        if (!typeChoice) return;

        typeChoice = parseInt(typeChoice.trim());
        if (typeChoice < 1 || typeChoice > 6) {
            alert('Invalid selection');
            return;
        }

        const reportType = reportTypes[typeChoice - 1].value;
        const reason = prompt('Please provide details about your report (required):');

        if (!reason || !reason.trim()) {
            alert('A reason is required to submit a report.');
            return;
        }

        submitReport(reportType, reason.trim());
    }

    async function submitReport(reportType, reason) {
        try {
            const response = await fetch(`/users/${receiverId}/report`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    report_type: reportType,
                    reason: reason
                })
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert(data.message || 'Report submitted successfully. An admin will review your report.');
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Report error:', error);
            alert('Failed to submit report. Please try again.');
        }
    }

    function appendMessage(msg) {
        let isMine = msg.sender_id == {{ auth()->id() }};

        let html = `
            <div class="message ${isMine ? 'sent' : 'received'}">
                ${msg.message}
            </div>
        `;

        document.getElementById('messages')
            .insertAdjacentHTML('beforeend', html);
    }

    function scrollToBottom() {
        let chatBox = document.getElementById("chat-box");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }
</script>
@endpush
@endsection
