<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RefreshCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Regenerate CSRF token on every request
        $request->session()->regenerateToken();
        
        // Add CSRF token to response headers for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $response = $next($request);
            $response->headers->set('X-CSRF-TOKEN', csrf_token());
            return $response;
        }

        return $next($request);
    }
}
