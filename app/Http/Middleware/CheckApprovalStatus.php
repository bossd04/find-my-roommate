<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovalStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_approved) {
            // If user is not approved and trying to access protected routes
            if (!$request->routeIs('registration.pending') && 
                !$request->routeIs('logout') && 
                !$request->is('admin.login') && 
                !$request->routeIs('admin.login.submit') &&
                !$request->routeIs('admin.dashboard') &&
                !$request->routeIs('admin.*')) {
                return redirect()->route('registration.pending');
            }
        }
        
        return $next($request);
    }
}
