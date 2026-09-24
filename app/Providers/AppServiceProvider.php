<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mendaftarkan Custom Provider kita ke dalam package Socialite
        if (class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            \Laravel\Socialite\Facades\Socialite::extend('sso', function ($app) {
                $config = $app['config']['services.sso'];
                return \Laravel\Socialite\Facades\Socialite::buildProvider(SsoSocialiteProvider::class, $config);
            });
        }
    }
}
