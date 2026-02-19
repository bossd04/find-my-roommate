<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
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

        if (!Schema::hasTable('settings')) {
            return;
        }

        try {
            $settings = \App\Models\Setting::all();
            foreach ($settings as $setting) {
                config([$setting->key => $setting->value]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to load settings: ' . $e->getMessage());
        }
    }
}
