<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\Auth\AuthRepository::class, function ($app) {
            return new \App\Repositories\Auth\AuthRepository();
        });

        $this->app->bind(\App\Services\AuthService::class, function ($app) {
            return new \App\Services\AuthService($app->make(\App\Repositories\Auth\AuthRepository::class));
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
