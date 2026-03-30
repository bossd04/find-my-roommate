<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Generate a 6-digit OTP code
     */
    public static function generateOtp()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if OTP is valid and not expired
     */
    public function isValid()
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed()
    {
        $this->update(['used' => true]);
    }

    /**
     * Clean up expired OTPs
     */
    public static function cleanupExpired()
    {
        self::where('expires_at', '<', now())
            ->orWhere('used', true)
            ->delete();
    }
}
