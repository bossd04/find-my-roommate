@extends('admin.layouts.app')

@section('title', 'Database Backups')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Database Backups</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your database backups</p>
        </div>
        <div class="mt-4 md:mt-0">
            <form action="{{ route('admin.backup.create') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i> Create New Backup
                </button>
            </form>
        </div>
    </div>

    <!-- Backup List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Available Backups</h2>
            <p class="text-sm text-gray-500">List of all database backups</p>
        </div>
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            File Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Size
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created At
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($backupFiles as $file)
                        @php
                            $filename = basename($file);
                            $filesize = round($file->getSize() / 1024, 2) . ' KB';
                            $createdAt = \Carbon\Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s');
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $filename }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $filesize }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $createdAt }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.backup.download', $filename) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <form action="{{ route('admin.backup.destroy', $filename) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this backup?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No backup files found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
