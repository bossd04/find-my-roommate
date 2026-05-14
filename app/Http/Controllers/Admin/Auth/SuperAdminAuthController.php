<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SuperAdminAuthController extends Controller
{
    /**
     * Show the superadmin login form.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.auth.superadmin-login');
    }

    /**
     * Handle superadmin login request.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt authentication
        if (!Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::guard('admin')->user();

        // Check if user is admin first
        if (!$user->is_admin) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'You do not have admin privileges.',
            ]);
        }

        // Check if user is superadmin
        if (!$user->is_superadmin) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'You do not have superadmin privileges. Access denied.',
            ]);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('admin.superadmin.dashboard'));
    }

    /**
     * Logout superadmin.
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }
}
