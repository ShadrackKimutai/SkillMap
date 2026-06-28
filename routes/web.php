<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PhoneOTPController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\TaskerVerificationController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Tasker\DashboardController as TaskerDashboard;
use App\Http\Controllers\Tasker\ProfileController as TaskerProfileController;
use App\Http\Controllers\Tasker\QuoteController as TaskerQuoteController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\JobRequestController;
use App\Http\Controllers\User\QuoteController as UserQuoteController;
use App\Http\Controllers\User\RatingController;

Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'tasker' => redirect()->route('tasker.dashboard'),
            'user' => redirect()->route('user.dashboard'),
        };
    }
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
            ->name('admin.dashboard')
            ->middleware('admin');

        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::resource('taskers', TaskerVerificationController::class)->only(['index', 'show']);
            Route::post('/taskers/{tasker}/approve', [TaskerVerificationController::class, 'approve'])->name('taskers.approve');
            Route::post('/taskers/{tasker}/reject', [TaskerVerificationController::class, 'reject'])->name('taskers.reject');

            Route::resource('trades', TradeController::class);
            Route::resource('specializations', SpecializationController::class);
            Route::resource('languages', LanguageController::class)->only(['index', 'create', 'store', 'destroy']);
        });

        Route::get('/tasker/dashboard', [TaskerDashboard::class, 'index'])
            ->name('tasker.dashboard')
            ->middleware('tasker');

        Route::middleware('tasker')->prefix('tasker')->name('tasker.')->group(function () {
            Route::get('/profile/edit', [TaskerProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [TaskerProfileController::class, 'update'])->name('profile.update');
            Route::get('/profile', [TaskerProfileController::class, 'show'])->name('profile.show');

            Route::resource('quotes', TaskerQuoteController::class)->only(['index', 'create', 'store', 'show']);
        });

        Route::post('/phone-otp/send', [PhoneOTPController::class, 'send'])->name('phone-otp.send');
        Route::get('/verify-phone-otp', [PhoneOTPController::class, 'create'])->name('verify.phone.otp');
        Route::post('/verify-phone-otp', [PhoneOTPController::class, 'verify'])->name('phone-otp.verify');

        Route::get('/user/dashboard', [UserDashboard::class, 'index'])
            ->name('user.dashboard')
            ->middleware('user');

        Route::middleware('user')->prefix('user')->name('user.')->group(function () {
            Route::get('/search', [SearchController::class, 'index'])->name('search.index');
            Route::post('/search', [SearchController::class, 'search'])->name('search.results');

            Route::resource('job-requests', JobRequestController::class);
            Route::get('/quotes', [UserQuoteController::class, 'index'])->name('quotes.index');
            Route::get('/quotes/{quote}', [UserQuoteController::class, 'show'])->name('quotes.show');
            Route::post('/quotes/{quote}/accept', [UserQuoteController::class, 'accept'])->name('quotes.accept');
            Route::post('/quotes/{quote}/reject', [UserQuoteController::class, 'reject'])->name('quotes.reject');

            Route::get('/job-requests/{jobRequest}/rate', [RatingController::class, 'create'])->name('ratings.create');
            Route::post('/job-requests/{jobRequest}/rate', [RatingController::class, 'store'])->name('ratings.store');
        });
    });
});

require __DIR__.'/auth.php';

