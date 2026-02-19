@props(['notification'])

@php
    $bgColor = match($notification->type) {
        'roommate_request_accepted' => 'bg-green-50 border-green-200',
        'match_request' => 'bg-blue-50 border-blue-200',
        'match_response' => $notification->data['status'] === 'accepted' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200',
        default => 'bg-white border-gray-200'
    };
    
    $icon = match($notification->type) {
        'roommate_request_accepted' => 'check-circle',
        'match_request' => 'user-plus',
        'match_response' => $notification->data['status'] === 'accepted' ? 'check-circle' : 'x-circle',
        default => 'bell'
    };
    
    $iconColor = match($notification->type) {
        'roommate_request_accepted' => 'text-green-500',
        'match_request' => 'text-blue-500',
        'match_response' => $notification->data['status'] === 'accepted' ? 'text-green-500' : 'text-yellow-500',
        default => 'text-gray-400'
    };
@endphp

<div class="p-4 border rounded-lg mb-2 {{ $bgColor }} border-l-4 {{ $notification->read_at ? 'opacity-75' : '' }}"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">
                {{ $notification->message }}
            </p>
            <div class="mt-2 flex space-x-4">
                @if($notification->type === 'roommate_request_accepted' || ($notification->type === 'match_response' && $notification->data['status'] === 'accepted'))
                    <a href="{{ route('messages.create', ['user' => $notification->data['from_user_id']]) }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Start Conversation
                    </a>
                @endif
                <span class="text-sm text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button @click="
                    fetch('{{ route('notifications.read', $notification->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(() => show = false)
                " class="inline-flex rounded-md p-1.5 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
