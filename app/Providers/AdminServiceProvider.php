<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the admin middleware
        $this->app->singleton('admin', function ($app) {
            return new \App\Http\Middleware\AdminMiddleware();
        });
    }

    public function boot()
    {
        // Load admin routes
        Route::middleware('web')
            ->namespace('App\Http\Controllers\Admin')
            ->group(base_path('routes/admin.php'));

        // Register the admin middleware alias
        $router = $this->app['router'];
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
    }
}
