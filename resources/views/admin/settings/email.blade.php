@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Email Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Configure your email server settings</p>
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
                    <a href="{{ route('admin.settings.system') }}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-md group">
                        <i class="fas fa-server mr-3"></i>
                        System Information
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md group">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </a>
                </nav>
                <div class="p-4 border-t border-gray-200">
                    <form action="{{ route('admin.settings.test-email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="test@example.com" required>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane mr-2"></i> Send Test Email
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('admin.settings.update-email') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Mail Driver -->
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                <select id="mail_driver" name="mail_driver" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="smtp" {{ config('mail.default') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mailgun" {{ config('mail.default') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ config('mail.default') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="mail" {{ config('mail.default') === 'mail' ? 'selected' : '' }}>PHP Mail</option>
                                    <option value="sendmail" {{ config('mail.default') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ config('mail.default') === 'log' ? 'selected' : '' }}>Log File</option>
                                </select>
                            </div>

                            <!-- SMTP Configuration -->
                            <div id="smtp-config" class="border-l-4 border-indigo-200 pl-4 py-2">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">SMTP Configuration</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mail_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                        <input type="text" name="mail_host" id="mail_host" value="{{ config('mail.mailers.smtp.host') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mail_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                        <input type="number" name="mail_port" id="mail_port" value="{{ config('mail.mailers.smtp.port') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mail_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                                        <input type="text" name="mail_username" id="mail_username" value="{{ config('mail.mailers.smtp.username') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mail_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                                        <input type="password" name="mail_password" id="mail_password" value="" placeholder="Leave blank to keep current" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mail_encryption" class="block text-sm font-medium text-gray-700">Encryption</label>
                                        <select id="mail_encryption" name="mail_encryption" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="tls" {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ empty(config('mail.mailers.smtp.encryption')) ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Mailgun Configuration -->
                            <div id="mailgun-config" class="border-l-4 border-indigo-200 pl-4 py-2 hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Mailgun Configuration</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mailgun_domain" class="block text-sm font-medium text-gray-700">Mailgun Domain</label>
                                        <input type="text" name="mailgun_domain" id="mailgun_domain" value="{{ config('services.mailgun.domain') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mailgun_secret" class="block text-sm font-medium text-gray-700">Mailgun Secret</label>
                                        <input type="password" name="mailgun_secret" id="mailgun_secret" value="" placeholder="Leave blank to keep current" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mailgun_endpoint" class="block text-sm font-medium text-gray-700">Mailgun Endpoint</label>
                                        <input type="text" name="mailgun_endpoint" id="mailgun_endpoint" value="{{ config('services.mailgun.endpoint', 'api.mailgun.net') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- SES Configuration -->
                            <div id="ses-config" class="border-l-4 border-indigo-200 pl-4 py-2 hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Amazon SES Configuration</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ses_key" class="block text-sm font-medium text-gray-700">SES Key</label>
                                        <input type="text" name="ses_key" id="ses_key" value="{{ config('services.ses.key') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="ses_secret" class="block text-sm font-medium text-gray-700">SES Secret</label>
                                        <input type="password" name="ses_secret" id="ses_secret" value="" placeholder="Leave blank to keep current" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="ses_region" class="block text-sm font-medium text-gray-700">SES Region</label>
                                        <input type="text" name="ses_region" id="ses_region" value="{{ config('services.ses.region', 'us-east-1') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Email From Settings -->
                            <div class="border-t border-gray-200 pt-4 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Email From Settings</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Address</label>
                                        <input type="email" name="mail_from_address" id="mail_from_address" value="{{ config('mail.from.address') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                                        <input type="text" name="mail_from_name" id="mail_from_name" value="{{ config('mail.from.name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-5">
                                <div class="flex justify-end">
                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mailDriver = document.getElementById('mail_driver');
        const smtpConfig = document.getElementById('smtp-config');
        const mailgunConfig = document.getElementById('mailgun-config');
        const sesConfig = document.getElementById('ses-config');
        
        function toggleConfigSections() {
            // Hide all config sections first
            smtpConfig.classList.add('hidden');
            mailgunConfig.classList.add('hidden');
            sesConfig.classList.add('hidden');
            
            // Show the selected config section
            if (mailDriver.value === 'smtp') {
                smtpConfig.classList.remove('hidden');
            } else if (mailDriver.value === 'mailgun') {
                mailgunConfig.classList.remove('hidden');
            } else if (mailDriver.value === 'ses') {
                sesConfig.classList.remove('hidden');
            }
        }
        
        // Initial toggle
        toggleConfigSections();
        
        // Toggle on change
        mailDriver.addEventListener('change', toggleConfigSections);
    });
</script>
@endpush
@endsection
