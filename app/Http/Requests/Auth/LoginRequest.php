<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        
        // Normalize email to lowercase for case-insensitive comparison
        $email = strtolower(trim($credentials['email']));
        
        // Log the login attempt for debugging
        \Log::info('Login attempt', [
            'original_email' => $credentials['email'],
            'normalized_email' => $email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Find user with case-insensitive email match
        $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$email])->first();
        
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            
            \Log::warning('Login failed: No user found with this email', [
                'attempted_email' => $email,
                'ip' => request()->ip()
            ]);
            
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Check if the user is active
        if (isset($user->is_active) && !$user->is_active) {
            \Log::warning('Login failed: User account is inactive', [
                'user_id' => $user->id,
                'email' => $email
            ]);
            
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // Check if the password is correct
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($this->throttleKey());
            
            \Log::warning('Login failed: Invalid password', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => request()->ip()
            ]);
            
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // If we got here, the credentials are valid
        // Manually log the user in
        Auth::login($user, $this->boolean('remember'));
        
        // Update last login timestamp
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);
        
        // Regenerate the session
        $this->session()->regenerate();
        
        // Clear the login attempts
        RateLimiter::clear($this->throttleKey());
        
        // Log successful login
        \Log::info('User logged in successfully', [
            'user_id' => $user->id, 
            'email' => $user->email,
            'session_id' => session()->getId()
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
