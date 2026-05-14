@extends('layouts.app-with-sidebar')

@section('content')
<!-- Content wrapper with transparent background to show living room image -->
<div style="background: transparent; min-height: calc(100vh - 200px); margin: -32px; padding: 32px;">
    <!-- Hero Section with semi-transparent dark overlay -->
    <div class="mb-8 rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(26,26,46,0.7) 50%, rgba(22,33,62,0.7) 100%); backdrop-filter: blur(5px);">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 left-0 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
                        <div class="absolute top-20 right-20 w-32 h-32 bg-blue-600 rounded-full blur-2xl animate-pulse delay-75"></div>
                        <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-blue-700 rounded-full blur-3xl animate-pulse delay-100"></div>
                        <div class="absolute bottom-10 right-10 w-24 h-24 bg-blue-500 rounded-full blur-xl animate-pulse delay-150"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold mb-2">Messages 💬</h1>
                        <p class="text-blue-100 text-lg">Connect with potential roommates and discuss living arrangements.</p>
                    </div>
                </div>
                
                <!-- Main Messages Content -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl overflow-hidden border border-blue-200">
                    <div class="flex flex-col md:flex-row h-[600px]">
                        <!-- Sidebar with conversations -->
                        <div class="w-full md:w-1/3 border-r border-gray-600 overflow-y-auto bg-gray-800">
                            <div class="bg-gray-900 p-4 rounded-tl-2xl border-b border-gray-600">
                                <h3 class="font-bold text-white text-lg">Roommate Conversations</h3>
                                <p class="text-sm text-gray-400">Connect with potential roommates</p>
                            </div>
                            <div class="divide-y divide-gray-700">
                                @forelse($conversations as $conversation)
                                    @php $isMuted = in_array($conversation->user->id, $mutedUserIds ?? []); @endphp
                                    <a href="{{ route('messages.show', $conversation->user) }}" 
                                       id="conversation-{{ $conversation->user->id }}"
                                       data-user-id="{{ $conversation->user->id }}"
                                       class="conversation-item block p-2 hover:bg-gray-50 {{ request()->route('user') && request()->route('user')->id === $conversation->user->id ? 'bg-blue-100 shadow-inner border-l-4 border-blue-500' : '' }} transition-colors duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 relative">
                                                <div class="h-8 w-8 rounded-full overflow-hidden {{ $isMuted ? 'opacity-70' : '' }}">
                                                    @if($conversation->user->avatar_url || $conversation->user->profile_photo_url)
                                                        <img src="{{ $conversation->user->avatar_url ?? $conversation->user->profile_photo_url }}" 
                                                             alt="{{ $conversation->user->fullName() }}" 
                                                             class="h-full w-full object-cover">
                                                    @else
                                                        <div class="h-full w-full bg-gray-700 flex items-center justify-center text-blue-400 text-xs font-medium">
                                                            {{ strtoupper(substr($conversation->user->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->user->last_name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($conversation->unread_count > 0 && !$isMuted)
                                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate {{ $isMuted ? 'text-gray-400' : '' }}">
                                                        {{ $conversation->user->first_name }} {{ $conversation->user->last_name }}
                                                    </p>
                                                    @if($isMuted)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-600 text-white" title="Notifications muted">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                                            </svg>
                                                            Muted
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    {{ Str::limit($conversation->last_message->content ?? 'No messages yet', 30) }}
                                                </p>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($conversation->last_message)
                                                    @if($conversation->last_message->created_at->isToday())
                                                        {{ $conversation->last_message->created_at->format('g:i A') }}
                                                    @elseif($conversation->last_message->created_at->isYesterday())
                                                        Yesterday {{ $conversation->last_message->created_at->format('g:i A') }}
                                                    @else
                                                        {{ $conversation->last_message->created_at->format('M j, g:i A') }}
                                                    @endif
                                                @endif
                                                @if($conversation->unread_count > 0)
                                                    <span class="ml-1 inline-block h-2 w-2 rounded-full bg-red-500"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                                        <p class="mt-1 text-sm text-gray-500">Start a conversation with your potential roommates!</p>
                                        <div class="mt-6">
                                            <a href="{{ route('matches.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Find Roommates
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chat area -->
                        <div class="flex-1 flex flex-col bg-gray-50">
                            @if(isset($user) && $user->id !== auth()->id())
                            <!-- Messages display area -->
                            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                                @forelse($messages as $message)
                                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-xs lg:max-w-md">
                                            <div class="{{ $message->sender_id === auth()->id() 
                                                ? 'bg-blue-600 text-white' 
                                                : 'bg-gray-100 text-gray-800 border border-gray-200' 
                                            }} rounded-lg px-4 py-2 shadow">
                                                <p class="text-sm">{{ $message->content }}</p>
                                                @if($message->image)
                                                <div class="mt-2">
                                                    <a href="{{ route('message.image', ['filename' => basename($message->image)]) }}" target="_blank" class="block">
                                                        <img src="{{ route('message.image', ['filename' => basename($message->image)]) }}" 
                                                             alt="Attached image" 
                                                             class="max-w-full max-h-32 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                                {{ $message->created_at->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-500 mt-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No messages yet. Start the conversation!</p>
                                    </div>
                                @endforelse
                            </div>
                            @endif

                            <!-- Message input -->
                            <div class="border-t border-blue-200 p-4 bg-white">
                                @if(isset($user) && $user->id !== auth()->id())
                                <form action="{{ route('messages.store', ['user' => $user->id]) }}" method="POST" class="flex items-center w-full">
                                    @csrf
                                    <input type="text" name="message" placeholder="Type a message..." class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 placeholder-gray-500" required>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm rounded-r-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Send
                                    </button>
                                </form>
                                @else
                                <div class="flex items-center justify-center w-full h-full text-gray-500 text-sm">
                                    @if(isset($user) && $user->id === auth()->id())
                                        You cannot message yourself
                                    @else
                                        Select a conversation to start messaging
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection
