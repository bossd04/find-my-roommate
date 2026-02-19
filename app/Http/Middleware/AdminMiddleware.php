<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::guard('admin')->user()->is_admin) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You do not have permission to access the admin area.']);
        }

        // Set the intended URL for redirecting after login
        if (!$request->is('admin/*')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
