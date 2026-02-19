<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RefreshTokenMiddleware
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
        $response = $next($request);
        
        // Only process HTML responses
        if ($request->wantsJson() || !$this->isHtmlResponse($response)) {
            return $response;
        }

        // If user is authenticated, refresh the CSRF token on every request
        if (Auth::check()) {
            $request->session()->regenerateToken();
            
            // Add CSRF token to response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                $response->headers->set('X-CSRF-TOKEN', csrf_token());
            }
            
            // Add CSRF token to response headers for JavaScript access
            $response->headers->set('X-CSRF-Token', csrf_token());
        }

        return $response;
    }

    /**
     * Check if the response is an HTML response.
     *
     * @param  mixed  $response
     * @return bool
     */
    protected function isHtmlResponse($response)
    {
        return $response instanceof \Illuminate\Http\Response && 
               str_contains($response->headers->get('Content-Type'), 'text/html');
    }
}
