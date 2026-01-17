<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\TraccarUserProvider;
use Illuminate\Support\Facades\Auth;

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
    public function boot()
    {
        // Enregistrer le provider d'authentification Traccar
        Auth::provider('traccar_provider', function ($app, array $config) {
            return new TraccarUserProvider();
        });
    }
}
