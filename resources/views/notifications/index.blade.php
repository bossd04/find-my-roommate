<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            <div class="flex space-x-3">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 50%, #f5f3ff 100%);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section with Background -->
            <div class="mb-8 rounded-lg p-8 text-white relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #2563eb 0%, #9333ea 50%, #4f46e5 100%);">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute top-20 right-20 w-32 h-32 bg-blue-300 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-purple-300 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-24 h-24 bg-indigo-300 rounded-full blur-xl"></div>
                </div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2">Notifications 🔔</h1>
                    <p class="text-blue-100 text-lg">Stay updated with your roommate matches and activity.</p>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="bg-white rounded-lg shadow-md">
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->read_at ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            @if(!$notification->read_at)
                                                <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                            @else
                                                <div class="w-3 h-3 bg-gray-300 rounded-full mt-2"></div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </h3>
                                                <span class="text-sm text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            
                                            <p class="mt-1 text-gray-600">
                                                {{ $notification->data['message'] ?? $notification->data['body'] ?? 'No message content' }}
                                            </p>
                                            
                                            @if(isset($notification->data['action_url']))
                                                <div class="mt-3">
                                                    <a href="{{ $notification->data['action_url'] }}" 
                                                       class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                        View Details
                                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                                    Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            You're all caught up! No new notifications at the moment.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
