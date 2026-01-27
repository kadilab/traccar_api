<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware pour la gestion de la langue et sécurité
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\CheckUserActive::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\ThrottleLogin::class,
        ]);
        
        // Ajouter les middlewares web (session, cookies) aux routes API
        // pour partager l'authentification avec les pages web
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        
        // Alias pour les middlewares
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'admin.api' => \App\Http\Middleware\CheckAdminApi::class,
            'manager' => \App\Http\Middleware\CheckManager::class,
            'manager.api' => \App\Http\Middleware\CheckManagerApi::class,
            'active' => \App\Http\Middleware\CheckUserActive::class,
            'throttle.api' => \App\Http\Middleware\ThrottleApiRequests::class,
            'cache.api' => \App\Http\Middleware\CacheApiResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
