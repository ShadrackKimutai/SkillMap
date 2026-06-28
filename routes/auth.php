<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function () {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, request('remember'))) {
            request()->session()->regenerate();

            $dashboard = match(auth()->user()->role) {
                'admin' => 'admin.dashboard',
                'tasker' => 'tasker.dashboard',
                'user' => 'user.dashboard',
            };

            return redirect()->intended(route($dashboard));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    });
});

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

Route::get('/verify-email', function () {
    return auth()->user()->email_verified_at ? redirect(route('user.dashboard')) : view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', function () {
    auth()->user()->markEmailAsVerified();
    return redirect(route('user.dashboard'))->with('success', 'Email verified!');
})->middleware('auth', 'signed')->name('verification.verify');

Route::post('/verify-email/resend', function () {
    if (auth()->user()->email_verified_at) {
        return redirect(route('user.dashboard'));
    }

    auth()->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware('auth', 'throttle:6,1')->name('verification.send');
