<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Display the system settings page.
     *
     * @return \Illuminate\View\View
     */
    public function system()
    {
        $defaultConnection = config('database.default');
        $connectionConfig = config("database.connections.{$defaultConnection}");
        return view('admin.settings.system', [
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'serverOs' => php_uname('s') . ' ' . php_uname('r'),
            'phpVersion' => PHP_VERSION,
            'phpSapi' => php_sapi_name(),
            'phpMemoryLimit' => ini_get('memory_limit'),
            'phpMaxExecutionTime' => ini_get('max_execution_time') . ' seconds',
            'phpPostMaxSize' => ini_get('post_max_size'),
            'phpUploadMaxFilesize' => ini_get('upload_max_filesize'),
            'laravelVersion' => app()->version(),
            'databaseConnection' => $defaultConnection,
            'databaseInfo' => [
                'name' => $connectionConfig['database'] ?? 'N/A',
                'username' => $connectionConfig['username'] ?? 'N/A',
                'host' => $connectionConfig['host'] ?? 'N/A',
                'port' => $connectionConfig['port'] ?? 'N/A',
            ],
            'serverInfo' => [
                'ip' => request()->server('SERVER_ADDR'),
                'protocol' => request()->server('SERVER_PROTOCOL'),
                'httpHost' => request()->server('HTTP_HOST'),
                'serverName' => request()->server('SERVER_NAME'),
                'serverPort' => request()->server('SERVER_PORT'),
                'documentRoot' => request()->server('DOCUMENT_ROOT'),
            ],
            'storagePath' => storage_path(),
            'storageWritable' => is_writable(storage_path()),
            'bootstrapCacheWritable' => is_writable(base_path('bootstrap/cache')),
            'storageLinkExists' => file_exists(public_path('storage'))
        ]);
    }

    /**
     * Display the email settings page.
     *
     * @return \Illuminate\View\View
     */
    public function email()
    {
        return view('admin.settings.email', [
            'mailDriver' => config('mail.driver'),
            'mailHost' => config('mail.host'),
            'mailPort' => config('mail.port'),
            'mailUsername' => config('mail.username'),
            'mailEncryption' => config('mail.encryption'),
            'mailFromAddress' => config('mail.from.address'),
            'mailFromName' => config('mail.from.name'),
            'mailgunDomain' => config('services.mailgun.domain'),
            'mailgunSecret' => config('services.mailgun.secret') ? '••••••••' . substr(config('services.mailgun.secret'), -4) : null,
            'mailgunEndpoint' => config('services.mailgun.endpoint'),
            'sesKey' => config('services.ses.key'),
            'sesSecret' => config('services.ses.secret') ? '••••••••' . substr(config('services.ses.secret'), -4) : null,
            'sesRegion' => config('services.ses.region'),
            'mailers' => [
                'smtp' => 'SMTP',
                'mail' => 'PHP Mail',
                'sendmail' => 'Sendmail',
                'mailgun' => 'Mailgun',
                'ses' => 'Amazon SES',
                'log' => 'Log',
                'array' => 'Array',
            ],
            'encryptionTypes' => [
                'tls' => 'TLS',
                'ssl' => 'SSL',
                'null' => 'None',
            ]
        ]);
    }

    /**
     * Update email settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => ['required', 'string', 'in:smtp,sendmail,mailgun,ses,mail,log,array'],
            'mail_host' => ['required_if:mail_driver,smtp', 'nullable', 'string', 'max:255'],
            'mail_port' => ['required_if:mail_driver,smtp', 'nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl,null'],
            'mail_from_address' => ['required', 'string', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'mailgun_domain' => ['required_if:mail_driver,mailgun', 'nullable', 'string', 'max:255'],
            'mailgun_secret' => ['required_if:mail_driver,mailgun', 'nullable', 'string', 'max:255'],
            'mailgun_endpoint' => ['required_if:mail_driver,mailgun', 'nullable', 'string', 'max:255'],
            'ses_key' => ['required_if:mail_driver,ses', 'nullable', 'string', 'max:255'],
            'ses_secret' => ['required_if:mail_driver,ses', 'nullable', 'string', 'max:255'],
            'ses_region' => ['required_if:mail_driver,ses', 'nullable', 'string', 'max:255'],
        ]);

        try {
            // Update mail settings
            $this->updateEnvValue('MAIL_MAILER', $validated['mail_driver']);
            $this->updateEnvValue('MAIL_HOST', $validated['mail_host'] ?? '');
            $this->updateEnvValue('MAIL_PORT', $validated['mail_port'] ?? '');
            $this->updateEnvValue('MAIL_USERNAME', $validated['mail_username'] ?? '');
            
            if (!empty($validated['mail_password']) && $validated['mail_password'] !== '••••••••') {
                $this->updateEnvValue('MAIL_PASSWORD', $validated['mail_password']);
            }
            
            $this->updateEnvValue('MAIL_ENCRYPTION', $validated['mail_encryption'] ?? 'tls');
            $this->updateEnvValue('MAIL_FROM_ADDRESS', $validated['mail_from_address']);
            $this->updateEnvValue('MAIL_FROM_NAME', $validated['mail_from_name']);

            if ($validated['mail_driver'] === 'mailgun') {
                $this->updateEnvValue('MAILGUN_DOMAIN', $validated['mailgun_domain']);
                $this->updateEnvValue('MAILGUN_SECRET', $validated['mailgun_secret']);
                $this->updateEnvValue('MAILGUN_ENDPOINT', $validated['mailgun_endpoint'] ?? 'api.mailgun.net');
            }

            if ($validated['mail_driver'] === 'ses') {
                $this->updateEnvValue('AWS_ACCESS_KEY_ID', $validated['ses_key']);
                $this->updateEnvValue('AWS_SECRET_ACCESS_KEY', $validated['ses_secret']);
                $this->updateEnvValue('AWS_DEFAULT_REGION', $validated['ses_region']);
            }

            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');

            return redirect()
                ->route('admin.settings.email')
                ->with('success', 'Email settings updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update email settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the payment settings page.
     *
     * @return \Illuminate\View\View
     */
    public function payment()
    {
        return view('admin.settings.payment', [
            'monthly_amount' => config('payments.monthly_amount', 1000),
            'due_date' => config('payments.due_date', now()->format('Y-m-d')),
            'payment_method' => config('payments.method', 'manual'),
            'payment_instructions' => config('payments.instructions', 'Please pay your monthly fee at office or via bank transfer.'),
            'enable_reminders' => config('payments.enable_reminders', true),
            'reminder_days_before' => config('payments.reminder_days_before', 3),
            'overdue_days' => config('payments.overdue_days', 7),
        ]);
    }

    /**
     * Update payment settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'monthly_amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'in:manual,gcash,bank_transfer'],
            'payment_instructions' => ['nullable', 'string', 'max:1000'],
            'enable_reminders' => ['boolean'],
            'reminder_days_before' => ['required', 'integer', 'min:1', 'max:30'],
            'overdue_days' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        try {
            // Update payment settings
            $this->updateEnvValue('PAYMENTS_MONTHLY_AMOUNT', $validated['monthly_amount']);
            $this->updateEnvValue('PAYMENTS_DUE_DATE', $validated['due_date']);
            $this->updateEnvValue('PAYMENTS_METHOD', $validated['payment_method']);
            $this->updateEnvValue('PAYMENTS_INSTRUCTIONS', $validated['payment_instructions'] ?? '');
            $this->updateEnvValue('PAYMENTS_ENABLE_REMINDERS', $validated['enable_reminders'] ? 'true' : 'false');
            $this->updateEnvValue('PAYMENTS_REMINDER_DAYS_BEFORE', $validated['reminder_days_before']);
            $this->updateEnvValue('PAYMENTS_OVERDUE_DAYS', $validated['overdue_days']);
            
            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');
            
            return redirect()
                ->route('admin.settings.payment')
                ->with('success', 'Payment settings updated successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update payment settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the general settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.index', [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'app_theme' => config('app.theme', 'light'),
        ]);
    }

    /**
     * Display the design & theme settings page.
     *
     * @return \Illuminate\View\View
     */
    public function design()
    {
        return view('admin.settings.design', [
            'appTheme' => config('app.theme', 'light'),
            'primaryColor' => config('app.primary_color', '#6366f1'),
            'sidebarMode' => config('app.sidebar_mode', 'expanded'),
        ]);
    }

    /**
     * Update design & theme settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDesign(Request $request)
    {
        $validated = $request->validate([
            'app_theme' => ['required', 'string', 'in:light,dark,system'],
            'primary_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'sidebar_mode' => ['required', 'string', 'in:expanded,compact'],
        ]);

        try {
            $this->updateEnvValue('APP_THEME', $validated['app_theme']);
            $this->updateEnvValue('APP_PRIMARY_COLOR', $validated['primary_color']);
            $this->updateEnvValue('APP_SIDEBAR_MODE', $validated['sidebar_mode']);

            Artisan::call('config:clear');
            Artisan::call('config:cache');

            return redirect()
                ->route('admin.settings.design')
                ->with('success', 'Design & Theme settings updated successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update design settings: ' . $e->getMessage());
        }
    }

    /**
     * Update general settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|timezone',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        try {
            // Update environment variables
            $this->updateEnvValue('APP_NAME', $validated['app_name']);
            $this->updateEnvValue('APP_URL', rtrim($validated['app_url'], '/'));
            $this->updateEnvValue('APP_TIMEZONE', $validated['timezone']);
            $this->updateEnvValue('MAIL_FROM_ADDRESS', $validated['mail_from_address']);
            $this->updateEnvValue('MAIL_FROM_NAME', $validated['mail_from_name']);
            
            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');
            
            return redirect()
                ->route('admin.settings')
                ->with('success', 'Settings updated successfully.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Update an environment variable in the .env file.
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    private function updateEnvValue($key, $value)
    {
        $path = base_path('.env');
        
        if (!file_exists($path)) {
            return;
        }

        // Read the current content
        $env = file_get_contents($path);
        
        // Escape special characters in the value
        $escapedValue = str_replace('$', '\\$', $value);
        
        // Check if the key exists
        if (str_contains($env, "{$key}=")) {
            // Replace the existing key
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$escapedValue}",
                $env
            );
        } else {
            // Add the new key-value pair
            $env .= "\n{$key}={$escapedValue}\n";
        }
        
        // Write the updated content back to the file
        file_put_contents($path, $env);
        
        // Update the current environment
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}