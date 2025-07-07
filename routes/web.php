<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Posts;
use Laravel\Fortify\Fortify;
// Fortify authentication
Fortify::loginView(function () {
    return view('livewire.auth.login');
});

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
// Route::middleware(['auth', 'verified'])->group(function () {
    
//     Route::get('/posts', Posts::class)->name('posts');
// });
Route::get('/', Posts::class)->name('posts');


// Route::get('/', function () {
//     return view('welcome');
// });
