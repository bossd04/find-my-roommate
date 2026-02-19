<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class HandleTokenMismatch
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
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Please refresh the page and try again.',
                    'refresh_token' => csrf_token()
                ], 419);
            }

            // For non-ajax requests, redirect back with a new token
            return redirect()
                ->back()
                ->withInput($request->except('_token'))
                ->withErrors(['_token' => 'Your session has expired. Please try again.']);
        }
    }
}
