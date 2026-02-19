<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Roommate Messages') }}
            </h2>
            <div class="relative">
                <input type="text" placeholder="Search messages..." class="pl-10 pr-4 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
        <div class="bg-black bg-opacity-70 min-h-screen py-8">
            <div class="max-w-1xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl shadow-1xl overflow-hidden">
                    <div class="flex h-[600px] border border-gray-200 rounded-xl overflow-hidden">
                        <!-- Sidebar with conversations -->
                        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white/80 overflow-y-auto">
                            <div class="p-4 border-b border-gray-200 bg-indigo-600 text-white">
                                <h3 class="font-semibold text-lg">Roommate Conversations</h3>
                                <p class="text-sm text-indigo-100">Connect with potential roommates</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse($conversations as $conversation)
                                    <a href="{{ route('messages.show', $conversation->user) }}" 
                                       class="block p-2 hover:bg-gray-50 {{ request()->route('user') && request()->route('user')->id === $conversation->user->id ? 'bg-indigo-50' : '' }} transition-colors duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 relative">
                                                <div class="h-8 w-8 rounded-full overflow-hidden">
                                                    @if($conversation->user->avatar && Storage::exists($conversation->user->avatar))
                                                        <img src="{{ Storage::url($conversation->user->avatar) }}?{{ time() }}" 
                                                             alt="{{ $conversation->user->fullName() }}" 
                                                             class="h-full w-full object-cover">
                                                    @else
                                                        <div class="h-full w-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-medium">
                                                            {{ strtoupper(substr($conversation->user->first_name, 0, 1)) }}{{ strtoupper(substr($conversation->user->last_name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($conversation->unread_count > 0)
                                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $conversation->user->first_name }} {{ $conversation->user->last_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ Str::limit($conversation->last_message->content ?? 'No messages yet', 30) }}
                                                </p>
                                            </div>
                                            <div class="text-xs text-gray-500">
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
                                    <div class="p-6 text-center text-black-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium">No conversations yet</h3>
                                        <p class="mt-1 text-sm">Start a conversation with your potential roommates!</p>
                                        <div class="mt-6">
                                            <a href="{{ route('matches.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                        <div class="flex-1 flex flex-col">

                            <!-- Message input -->
                            <div class="p-4 border-t">
                                @if(isset($user) && $user->id !== auth()->id())
                                <form action="{{ route('messages.store', ['user' => $user->id]) }}" method="POST" class="flex items-center w-full">
                                    @csrf
                                    <input type="text" name="message" placeholder="Type a message..." class="flex-1 border rounded-l-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Send
                                    </button>
                                </form>
                                @else
                                <div class="flex items-center justify-center w-full py-2 text-black-500 text-sm">
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
        </div>
    </div>
</x-app-layout>
