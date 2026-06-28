<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class PhoneOTPController extends Controller
{
    public function create(): View
    {
        return view('auth.verify-phone-otp');
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate(['phone' => ['required', 'string', 'min:10', 'max:15']]);

        $otp = rand(100000, 999999);
        Cache::put('phone_otp_' . auth()->id(), $otp, now()->addMinutes(10));

        Mail::raw("Your phone verification code is: $otp", function ($message) {
            $message->to(auth()->user()->email)->subject('Phone Verification Code');
        });

        auth()->user()->update(['phone' => $request->phone]);

        return redirect()->route('verify.phone.otp')->with('message', 'OTP sent to your email');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['otp' => ['required', 'numeric', 'digits:6']]);

        $cachedOTP = Cache::get('phone_otp_' . auth()->id());

        if ($cachedOTP == $request->otp) {
            auth()->user()->update(['phone_verified_at' => now()]);
            Cache::forget('phone_otp_' . auth()->id());

            return redirect()->route('tasker.dashboard')->with('success', 'Phone verified');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }
}
