<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Prevent redirect loop if already on login page
        if ($request->is('admin/login')) {
            return $next($request);
        }

        if ($request->is('admin*') && !Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if ($request->is('admin*') && Auth::guard('admin')->check() && !Auth::guard('admin')->user()->is_admin) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You do not have permission to access the admin area.']);
        }

        return $next($request);
    }
}
