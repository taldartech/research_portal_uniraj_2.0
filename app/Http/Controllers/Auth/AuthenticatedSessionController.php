<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        return view('auth.login', [
            'email' => old('email'),
            'otp_sent' => session('otp_sent', false)
        ]);
    }

    /**
     * Send OTP to user's email (after email+password validation)
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // First verify email and password
        $credentials = $request->only('email', 'password');
        
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();
        
        // Check user type restrictions
        $allowedStaffTypes = ['staff', 'supervisor', 'hod', 'dean'];
        if (!in_array($user->user_type, $allowedStaffTypes)) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'You do not have staff access.',
            ]);
        }

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

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('staff.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
