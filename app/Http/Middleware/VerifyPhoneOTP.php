<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPhoneOTP
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'tasker' && auth()->user()->phone_verified_at === null) {
            return redirect()->route('verify.phone.otp');
        }

        return $next($request);
    }
}
