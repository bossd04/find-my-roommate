<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // This will authenticate the user and log them in
            $request->authenticate();

            // Check if the user is authenticated
            if (!auth()->check()) {
                throw new \RuntimeException('Authentication failed after successful authentication');
            }

            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();

            // Log the successful login
            \Log::info('User logged in successfully', [
                'user_id' => auth()->id(),
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Redirect based on user role
            if (auth()->user()->is_admin) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }
            
            return redirect()->intended(route('dashboard', absolute: false));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log the failed login attempt
            \Log::warning('Login failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'errors' => $e->errors()
            ]);
            
            throw $e;
        } catch (\Exception $e) {
            // Log any other exceptions
            \Log::error('Login error', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return back with error
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.'
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
