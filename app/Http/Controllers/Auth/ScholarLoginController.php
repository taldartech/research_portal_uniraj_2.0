<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScholarLoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.scholar-login', [
            'email' => old('email'),
            'otp_sent' => session('otp_sent', false)
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // First verify email and password
        $credentials = $request->only('email', 'password') + ['user_type' => 'scholar'];
        
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // If password is correct, send OTP
        $this->otpService->sendOtp($user);
        
        // Logout the user (we'll login again after OTP verification)
        Auth::logout();

        return back()->with([
            'status' => 'OTP has been sent to your email address.',
            'otp_sent' => true,
            'email' => $request->email
        ])->withInput($request->only('email'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $request->email)
            ->where('user_type', 'scholar')
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        if (! $this->otpService->verifyOtp($user, $request->otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('scholar.dashboard', absolute: false));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('scholar.login');
    }
}
