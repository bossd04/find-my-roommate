<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OtpPasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to user's email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        // Clean up any existing expired OTPs for this email
        PasswordResetOtp::where('email', $request->email)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->delete();

        // Generate new OTP
        $otpCode = PasswordResetOtp::generateOtp();
        
        // Create OTP record
        PasswordResetOtp::create([
            'email' => $request->email,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(15), // OTP expires in 15 minutes
            'used' => false,
        ]);

        // Send email with OTP
        try {
            Mail::raw(
                "Your password reset OTP is: {$otpCode}\n\nThis OTP will expire in 15 minutes.\n\nIf you didn't request this, please ignore this email.",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Password Reset OTP - Find My Roommate');
                }
            );

            return redirect()->route('password.otp.verify')
                ->with('success', 'OTP has been sent to your email address.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    /**
     * Show OTP verification form.
     */
    public function showOtpVerificationForm()
    {
        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and show password reset form.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        // Find valid OTP
        $otp = PasswordResetOtp::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Store verified email in session
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.reset.form')
            ->with('success', 'OTP verified successfully. Please set your new password.');
    }

    /**
     * Show password reset form.
     */
    public function showResetPasswordForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please verify your OTP first.');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please verify your OTP first.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        session()->forget('password_reset_email');

        return redirect()->route('login')
            ->with('success', 'Password has been reset successfully. Please login with your new password.');
    }
}
