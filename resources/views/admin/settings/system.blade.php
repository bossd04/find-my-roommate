@extends('admin.layouts.app')

@section('title', 'System Information')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">System Information</h1>
        <p class="mt-1 text-sm text-gray-600">View system details and server information</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Navigation -->
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Settings</h2>
                </div>
                <nav class="p-2">
                    <a href="{{ route('admin.settings') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-cog mr-3"></i>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.system') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md group">
                        <i class="fas fa-server mr-3"></i>
                        System Information
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </a>
                </nav>
                <div class="p-4 border-t border-gray-200">
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-sync-alt mr-2"></i> Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Server Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Server Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Server Software</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $serverSoftware }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Server IP</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ request()->server('SERVER_ADDR') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Server Protocol</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ request()->server('SERVER_PROTOCOL') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">HTTP Host</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ request()->server('HTTP_HOST') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Server OS</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $serverOs }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Server Port</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ request()->server('SERVER_PORT') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- PHP Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">PHP Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $phpVersion }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP SAPI</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ php_sapi_name() }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP Memory Limit</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ini_get('memory_limit') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP Max Execution Time</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ini_get('max_execution_time') }} seconds</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP Upload Max Filesize</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ini_get('upload_max_filesize') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">PHP Post Max Size</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ini_get('post_max_size') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Laravel Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Laravel Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $laravelVersion }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ app()->environment('production') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ app()->environment() }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ config('app.debug') ? 'ON' : 'OFF' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ config('app.timezone') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Locale</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ config('app.locale') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Cache Driver</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ config('cache.default') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Session Driver</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ config('session.driver') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Queue Connection</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ config('queue.default') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Database Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Database Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Database Connection</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($databaseConnection) }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Database Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $databaseInfo['name'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Database Username</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $databaseInfo['username'] }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Database Host</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $databaseInfo['host'] }}:{{ $databaseInfo['port'] }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Storage Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Storage Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Storage Path</dt>
                                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $storagePath }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Storage Link</dt>
                                        <dd class="mt-1">
                                            @if(file_exists(public_path('storage')))
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Created
                                                </span>
                                            @else
                                                <form action="{{ route('admin.settings.storage-link') }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                        Create Storage Link
                                                    </button>
                                                </form>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Permissions</dt>
                                        <dd class="mt-1">
                                            @php
                                                $storageWritable = is_writable(storage_path());
                                                $cacheWritable = is_writable(base_path('bootstrap/cache'));
                                            @endphp
                                            <ul class="text-sm text-gray-900 space-y-1">
                                                <li class="flex items-center">
                                                    @if($storageWritable)
                                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                    <span class="ml-1">Storage directory is {{ $storageWritable ? 'writable' : 'not writable' }}</span>
                                                </li>
                                                <li class="flex items-center">
                                                    @if($cacheWritable)
                                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                    <span class="ml-1">Cache directory is {{ $cacheWritable ? 'writable' : 'not writable' }}</span>
                                                </li>
                                            </ul>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- System Requirements -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">System Requirements</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <ul class="text-sm text-gray-900 space-y-2">
                                    @php
                                        $requirements = [
                                            'PHP >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
                                            'BCMath PHP Extension' => extension_loaded('bcmath'),
                                            'Ctype PHP Extension' => extension_loaded('ctype'),
                                            'Fileinfo PHP Extension' => extension_loaded('fileinfo'),
                                            'JSON PHP Extension' => extension_loaded('json'),
                                            'Mbstring PHP Extension' => extension_loaded('mbstring'),
                                            'OpenSSL PHP Extension' => extension_loaded('openssl'),
                                            'PDO PHP Extension' => extension_loaded('pdo'),
                                            'Tokenizer PHP Extension' => extension_loaded('tokenizer'),
                                            'XML PHP Extension' => extension_loaded('xml'),
                                            'cURL PHP Extension' => extension_loaded('curl'),
                                            'GD PHP Extension' => extension_loaded('gd'),
                                            'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
                                        ];
                                    @endphp
                                    
                                    @foreach($requirements as $requirement => $met)
                                        <li class="flex items-center">
                                            @if($met)
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            <span class="ml-1">{{ $requirement }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
