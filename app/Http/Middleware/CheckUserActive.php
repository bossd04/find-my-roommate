<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $authUser = Auth::user();
            
            // Query database FRESH to get real-time is_active status
            // Auth::user() may be cached in session
            $freshUser = User::where('id', $authUser->id)->first();
            
            if (!$freshUser) {
                // User no longer exists in database
                Auth::logout();
                $request->session()->invalidate();
                return redirect()->route('login');
            }
            
            // Log for debugging
            \Log::info('CheckUserActive middleware:', [
                'user_id' => $authUser->id,
                'auth_is_active' => $authUser->is_active,
                'db_is_active' => $freshUser->is_active,
                'url' => $request->url(),
            ]);
            
            // Check if user is inactive (use database value, not cached)
            if (!$freshUser->is_active) {
                // Log the deactivation detection
                \Log::warning('Deactivated user blocked:', [
                    'user_id' => $freshUser->id,
                    'email' => $freshUser->email,
                    'url' => $request->url(),
                    'ip' => $request->ip(),
                ]);
                
                // Logout the user
                Auth::logout();
                
                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect to deactivated page with message
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been deactivated by an administrator.',
                        'redirect' => route('account.deactivated')
                    ], 403);
                }
                
                return redirect()->route('account.deactivated')
                    ->with('deactivation_message', 'Your account was deactivated by admin.');
            }
        }
        
        return $next($request);
    }
}
