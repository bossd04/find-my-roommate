<div x-data="{ open: false, notifications: [], unreadCount: 0, loading: true }" 
     @notify.window="
        fetchNotifications();
        $dispatch('refresh-notifications');
     "
     class="relative">
    <button @click="open = !open" 
            @click.away="open = false"
            class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" 
              x-text="unreadCount"
              class="absolute -top-1 -right-1 inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-xs text-white">
        </span>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 max-h-96 overflow-y-auto"
         style="display: none;">
        <div class="py-1">
            <div class="px-4 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                <button @click="markAllAsRead()" 
                        class="text-xs text-blue-600 hover:text-blue-800 focus:outline-none"
                        x-show="unreadCount > 0">
                    Mark all as read
                </button>
            </div>
            
            <div x-show="loading" class="p-4 text-center">
                <svg class="animate-spin h-5 w-5 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <div x-show="!loading && notifications.length === 0" class="p-4 text-center text-sm text-gray-500">
                No new notifications
            </div>
            
            <template x-for="notification in notifications" :key="notification.id">
                <x-notification-item :notification="notification" />
            </template>
            
            <a href="{{ route('notifications.index') }}" 
               class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-gray-50 border-t border-gray-100">
                View all notifications
            </a>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifications', () => ({
                open: false,
                notifications: [],
                unreadCount: 0,
                loading: true,
                
                init() {
                    this.fetchNotifications();
                    
                    // Listen for notification events
                    window.Echo.private(`user.${this.$store.auth.user.id}`)
                        .listen('.notification.created', (e) => {
                            this.fetchNotifications();
                        });
                },
                
                fetchNotifications() {
                    this.loading = true;
                    fetch('{{ route("notifications.unread") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                        this.loading = false;
                    });
                },
                
                markAllAsRead() {
                    fetch('{{ route("notifications.mark-all-read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(() => {
                        this.unreadCount = 0;
                        this.notifications.forEach(notification => notification.read_at = new Date().toISOString());
                    });
                }
            }));
        });
    </script>
</div>
