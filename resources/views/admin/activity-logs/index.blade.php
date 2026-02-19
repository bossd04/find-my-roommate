<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">System Activity Logs</h3>
                        <div class="flex items-center space-x-3">
                            <button onclick="window.location.reload()" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                Refresh
                            </button>
                            <form method="GET" action="{{ route('admin.activity_logs.clear') }}" class="inline">
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Clear All Logs
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        View and manage system activity logs, user actions, and important events.
                    </p>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($error))
                        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            {{ $error }}
                        </div>
                    @endif

                    <!-- Activity Logs Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Timestamp
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            IP Address
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Details
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($activityLogs as $log)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ isset($log->created_at) ? \Carbon\Carbon::parse($log->created_at)->format('M j, Y H:i:s') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if(isset($log->user_name))
                                                    {{ $log->user_name }}
                                                @elseif(isset($log->user) && $log->user)
                                                    {{ $log->user->name }}
                                                @elseif(isset($log->causer_id) && $log->causer_id)
                                                    User ID: {{ $log->causer_id }}
                                                @else
                                                    System
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $log->event ?? $log->description ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $log->ip_address ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $log->description ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No activity logs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if(isset($activityLogs) && method_exists($activityLogs, 'links'))
                        <div class="mt-6">
                            {{ $activityLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
