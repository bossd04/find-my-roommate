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
        if ($request->is('admin*') && !Auth::check()) {
            return redirect()->route('admin.login');
        }

        if ($request->is('admin*') && Auth::check() && !Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You do not have permission to access the admin area.']);
        }

        return $next($request);
    }
}
