@extends('admin.layouts.app')

@section('title', 'Conversation')

@push('styles')
<style>
    .chat-container {
        max-height: 600px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .chat-container::-webkit-scrollbar {
        width: 6px;
    }
    .chat-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
    }
    .message-bubble.sent {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }
    .message-bubble.received {
        background: #f1f5f9;
        color: #1e293b;
        border-bottom-left-radius: 4px;
    }
    .dark .message-bubble.received {
        background: #334155;
        color: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.messages.index') }}" class="p-3 bg-white dark:bg-gray-800 rounded-xl text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-300 border-2 border-gray-100 dark:border-gray-700">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Conversation</h1>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    {{ $participant1->name }} <i class="fas fa-exchange-alt mx-2 text-xs"></i> {{ $participant2->name }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-bold text-gray-600 dark:text-gray-400">
                {{ $conversationMessages->count() }} messages
            </span>
        </div>
    </div>

    <!-- Conversation Container -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Chat Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Participant 1 -->
                            <div class="flex items-center gap-3">
                                @if(!empty($participant1->avatar))
                                    <img src="{{ route('avatar.serve', ['filename' => basename($participant1->avatar)]) }}" 
                                         alt="{{ $participant1->name }}"
                                         class="h-12 w-12 rounded-2xl object-cover border-2 border-white shadow-lg"
                                         onerror="this.className='h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black shadow-lg'">
                                @elseif(!empty($participant1->profile_photo_path))
                                    <img src="{{ route('profile.photo.serve', ['filename' => basename($participant1->profile_photo_path)]) }}" 
                                         alt="{{ $participant1->name }}"
                                         class="h-12 w-12 rounded-2xl object-cover border-2 border-white shadow-lg"
                                         onerror="this.className='h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black shadow-lg'">
                                @else
                                    <div class="h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-black shadow-lg">
                                        {{ strtoupper(substr($participant1->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $participant1->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $participant1->email }}</div>
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-arrows-alt-h"></i>
                            </div>
                            <!-- Participant 2 -->
                            <div class="flex items-center gap-3">
                                @if(!empty($participant2->avatar))
                                    <img src="{{ route('avatar.serve', ['filename' => basename($participant2->avatar)]) }}" 
                                         alt="{{ $participant2->name }}"
                                         class="h-12 w-12 rounded-2xl object-cover border-2 border-white shadow-lg"
                                         onerror="this.className='h-12 w-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white font-black shadow-lg'">
                                @elseif(!empty($participant2->profile_photo_path))
                                    <img src="{{ route('profile.photo.serve', ['filename' => basename($participant2->profile_photo_path)]) }}" 
                                         alt="{{ $participant2->name }}"
                                         class="h-12 w-12 rounded-2xl object-cover border-2 border-white shadow-lg"
                                         onerror="this.className='h-12 w-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white font-black shadow-lg'">
                                @else
                                    <div class="h-12 w-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white font-black shadow-lg">
                                        {{ strtoupper(substr($participant2->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $participant2->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $participant2->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-container p-6 space-y-4" id="chatContainer">
                    @php
                        $currentDate = null;
                    @endphp
                    
                    @forelse($conversationMessages as $msg)
                        @php
                            $messageDate = $msg->created_at->format('Y-m-d');
                            $isNewDate = $messageDate !== $currentDate;
                            $currentDate = $messageDate;
                            $isParticipant1 = $msg->sender_id === $participant1->id;
                        @endphp
                        
                        @if($isNewDate)
                            <!-- Date Separator -->
                            <div class="flex justify-center my-6">
                                <span class="px-4 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    {{ $msg->created_at->format('F j, Y') }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Message Bubble -->
                        <div class="flex {{ $isParticipant1 ? 'justify-end' : 'justify-start' }} mb-4">
                            <div class="flex items-end gap-2 max-w-[80%] {{ $isParticipant1 ? 'flex-row-reverse' : 'flex-row' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if(!empty($msg->sender->avatar))
                                        <img src="{{ route('avatar.serve', ['filename' => basename($msg->sender->avatar)]) }}" 
                                             alt="{{ $msg->sender->name }}"
                                             class="h-8 w-8 rounded-xl object-cover {{ $isParticipant1 ? 'bg-indigo-500' : 'bg-emerald-500' }}"
                                             onerror="this.className='h-8 w-8 rounded-xl flex items-center justify-center text-white font-bold text-xs {{ $isParticipant1 ? 'bg-indigo-500' : 'bg-emerald-500' }}'">
                                    @elseif(!empty($msg->sender->profile_photo_path))
                                        <img src="{{ route('profile.photo.serve', ['filename' => basename($msg->sender->profile_photo_path)]) }}" 
                                             alt="{{ $msg->sender->name }}"
                                             class="h-8 w-8 rounded-xl object-cover {{ $isParticipant1 ? 'bg-indigo-500' : 'bg-emerald-500' }}"
                                             onerror="this.className='h-8 w-8 rounded-xl flex items-center justify-center text-white font-bold text-xs {{ $isParticipant1 ? 'bg-indigo-500' : 'bg-emerald-500' }}'">
                                    @else
                                        <div class="h-8 w-8 rounded-xl {{ $isParticipant1 ? 'bg-indigo-500' : 'bg-emerald-500' }} flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Message Content -->
                                <div class="flex flex-col {{ $isParticipant1 ? 'items-end' : 'items-start' }}">
                                    <div class="message-bubble {{ $isParticipant1 ? 'sent' : 'received' }} px-4 py-3 rounded-2xl shadow-sm">
                                        @if($msg->subject && $msg->subject !== 'No subject' && $msg->subject !== 'Message')
                                            <div class="text-xs font-bold opacity-80 mb-1 {{ $isParticipant1 ? 'text-white/80' : 'text-gray-600 dark:text-gray-300' }}">
                                                {{ $msg->subject }}
                                            </div>
                                        @endif
                                        <div class="text-sm leading-relaxed">
                                            {!! nl2br(e($msg->body)) !!}
                                        </div>
                                        
                                        <!-- Attached Image -->
                                        @if($msg->image)
                                            <div class="mt-3">
                                                <a href="{{ route('message.image', ['filename' => basename($msg->image)]) }}" target="_blank" class="block">
                                                    <img src="{{ route('message.image', ['filename' => basename($msg->image)]) }}" 
                                                         alt="Attached image" 
                                                         class="max-w-full max-h-48 rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Timestamp -->
                                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                                        @if($msg->is_read)
                                            <i class="fas fa-check-double text-emerald-500"></i>
                                        @else
                                            <i class="fas fa-check text-gray-300"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-comment-slash text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No messages in this conversation</p>
                        </div>
                    @endforelse
                </div>

                <!-- Chat Input (Admin Reply) -->
                <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $participant1->id === auth()->id() ? $participant2->id : $participant1->id }}">
                        <input type="hidden" name="subject" value="Re: {{ $message->subject }}">
                        
                        <div class="flex-1 relative">
                            <textarea name="body" rows="2" placeholder="Type your message..." 
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl text-sm resize-none focus:ring-0 focus:border-indigo-500 transition-all"
                                required></textarea>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label for="image" class="p-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-indigo-600 hover:border-indigo-500 cursor-pointer transition-all" title="Attach image">
                                <i class="fas fa-image"></i>
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" class="hidden">
                            
                            <button type="submit" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all" title="Send message">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Actions Panel -->
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h4 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] mb-4">Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('admin.messages.create', ['reply_to' => $participant1->id === auth()->id() ? $participant2->id : $participant1->id, 'subject' => 'Re: ' . $message->subject]) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-wider rounded-xl hover:bg-indigo-700 transition-all">
                        <i class="fas fa-reply mr-2"></i> Reply
                    </a>
                    
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" 
                          onsubmit="return confirm('Delete this entire conversation? This will remove all messages between these users.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-rose-100 dark:bg-rose-900/30 text-rose-600 text-xs font-black uppercase tracking-wider rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Chat
                        </button>
                    </form>
                </div>
            </div>

            <!-- Participants Info -->
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                <h4 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] mb-4">Participants</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        @if(!empty($participant1->avatar))
                            <img src="{{ route('avatar.serve', ['filename' => basename($participant1->avatar)]) }}" 
                                 alt="{{ $participant1->name }}"
                                 class="h-10 w-10 rounded-xl object-cover"
                                 onerror="this.className='h-10 w-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white font-black'">
                        @elseif(!empty($participant1->profile_photo_path))
                            <img src="{{ route('profile.photo.serve', ['filename' => basename($participant1->profile_photo_path)]) }}" 
                                 alt="{{ $participant1->name }}"
                                 class="h-10 w-10 rounded-xl object-cover"
                                 onerror="this.className='h-10 w-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white font-black'">
                        @else
                            <div class="h-10 w-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white font-black">
                                {{ strtoupper(substr($participant1->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $participant1->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $participant1->email }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        @if(!empty($participant2->avatar))
                            <img src="{{ route('avatar.serve', ['filename' => basename($participant2->avatar)]) }}" 
                                 alt="{{ $participant2->name }}"
                                 class="h-10 w-10 rounded-xl object-cover"
                                 onerror="this.className='h-10 w-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black'">
                        @elseif(!empty($participant2->profile_photo_path))
                            <img src="{{ route('profile.photo.serve', ['filename' => basename($participant2->profile_photo_path)]) }}" 
                                 alt="{{ $participant2->name }}"
                                 class="h-10 w-10 rounded-xl object-cover"
                                 onerror="this.className='h-10 w-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black'">
                        @else
                            <div class="h-10 w-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black">
                                {{ strtoupper(substr($participant2->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $participant2->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $participant2->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversation Stats -->
            <div class="bg-indigo-600 rounded-[2rem] p-6 text-white shadow-xl">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-4 opacity-80">Stats</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold opacity-70">Total Messages</span>
                        <span class="text-lg font-black">{{ $conversationMessages->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold opacity-70">Started</span>
                        <span class="text-sm font-bold">{{ $conversationMessages->first() ? $conversationMessages->first()->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold opacity-70">Last Message</span>
                        <span class="text-sm font-bold">{{ $conversationMessages->last() ? $conversationMessages->last()->created_at->diffForHumans() : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Scroll to bottom of chat on load
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
    
    // Preview image before upload
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const label = this.previousElementSibling;
                label.classList.add('text-indigo-600', 'border-indigo-500');
                label.querySelector('i').classList.replace('fa-image', 'fa-check');
            }
        });
    }
</script>
@endpush
@endsection
