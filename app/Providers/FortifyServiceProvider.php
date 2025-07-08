<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Str;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Responses\CustomLoginViewResponse; // your custom response
use Laravel\Fortify\Contracts\LoginViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind interfaces to implementations
        $this->app->singleton(LoginViewResponse::class, CustomLoginViewResponse::class);
    }

    public function boot()
    {
        Fortify::ignoreRoutes();

        // Custom user actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Optional: Two-factor authentication redirect
        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            \App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable::class
        );

        // Rate limiting for login and two-factor
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Optional: View overrides
        // Fortify::loginView(fn () => view('auth.login'));
    }
}