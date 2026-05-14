<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            // Ensure we are not using sqlite if we are in production
            if (config('app.env') === 'production' && config('database.default') === 'sqlite') {
                return;
            }

            if (!Schema::hasTable('settings')) {
                return;
            }

            $settings = \App\Models\Setting::all();
            foreach ($settings as $setting) {
                config([$setting->key => $setting->value]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to load settings: ' . $e->getMessage());
        }
    }
}
