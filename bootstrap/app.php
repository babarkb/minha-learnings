<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function (): void {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
