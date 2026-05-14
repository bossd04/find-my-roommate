<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
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
        // Check if user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::guard('admin')->user();

        // Check if user is superadmin
        if (!$user->is_superadmin) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access the Super Admin area.');
        }

        return $next($request);
    }
}
