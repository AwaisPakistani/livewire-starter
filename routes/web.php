<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Livewire\{
    Posts,
};
use Laravel\Fortify\Fortify;
// Fortify authentication


Fortify::loginView(function () {
    return view('livewire.auth.login');
    // Route::get('login',[AuthenticatedSessionController::class, 'create'])->name('login');
});
Route::post('login',[AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Verification notice
Route::get('/email/verify', function () {
    Route::get('verify-email',[AuthController::class, 'verify_email'])->name('login.verify_email');
})->middleware('auth')->name('verification.notice');

// Verification link click
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Fortify::registerView(function () {
    return view('livewire.auth.register');
});

Fortify::requestPasswordResetLinkView(function () {
    return view('livewire.auth.forgot-password');
});

Fortify::resetPasswordView(function ($request) {
    return view('livewire.auth.reset-password', ['request' => $request]);
});

Fortify::verifyEmailView(function () {
    return view('livewire.auth.verify-email');
});

Fortify::confirmPasswordView(function () {
    return view('livewire.auth.confirm-password');
});

Fortify::twoFactorChallengeView(function () {
    return view('livewire.auth.two-factor-challenge');
});

//////////////////////////
// if want to login verified users
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/', function () {
//         return view('welcome');
//     });
//     Route::get('/posts', Posts::class)->name('posts');
// });

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/posts', Posts::class)->name('posts');
});
// Route::get('/', Posts::class)->name('posts');



