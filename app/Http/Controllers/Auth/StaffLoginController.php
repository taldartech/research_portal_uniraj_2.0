<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $attempt = Auth::attempt($credentials, $request->boolean('remember'));

        $allowedStaffTypes = ['staff', 'supervisor', 'hod', 'dean', 'da', 'so', 'ar', 'dr', 'hvc'];

        if (! $attempt) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (! in_array($user->user_type, $allowedStaffTypes)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => 'You do not have staff access.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('staff.dashboard', absolute: false));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
