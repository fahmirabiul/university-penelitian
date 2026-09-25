<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Penelitian;
use App\Observers\PenelitianObserver;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Penelitian::observe(PenelitianObserver::class);

        if (class_exists(Socialite::class)) {
            Socialite::extend('sso', function ($app) {
                $config = $app['config']['services.sso'];
                return Socialite::buildProvider(SsoSocialiteProvider::class, $config);
            });
        }
    }
}
