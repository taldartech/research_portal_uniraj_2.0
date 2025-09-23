<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types  // This parameter is no longer used for dynamic type checking
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        $allowedTypes = ['scholar', 'staff', 'supervisor', 'hod', 'dean', 'da', 'so', 'ar', 'dr', 'hvc']; // Include all user types

        if (! Auth::check() || ! in_array(Auth::user()->user_type, $allowedTypes)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
