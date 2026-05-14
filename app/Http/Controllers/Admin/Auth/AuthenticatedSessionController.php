<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            
            // Attempt to authenticate with admin guard
            if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
                // Check if the user is an admin
                if (!Auth::guard('admin')->user()->is_admin) {
                    Auth::guard('admin')->logout();
                    return back()->withErrors([
                        'email' => 'You do not have permission to access the admin area.',
                    ])->onlyInput('email');
                }

                $request->session()->regenerate();

                Log::info('Admin logged in', [
                    'user_id' => Auth::guard('admin')->id(),
                    'email' => $request->email,
                    'ip' => $request->ip(),
                ]);

                // Clear any leftover intended URL and redirect directly to admin dashboard
                $request->session()->forget('url.intended');
                return redirect()->route('admin.dashboard');
            }
            
            // If authentication fails
            throw new \Exception('Invalid credentials');

        } catch (\Exception $e) {
            Log::error('Admin login error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
