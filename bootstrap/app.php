<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureApprovedVendor;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            SetLocaleMiddleware::class,
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'vendor.approved' => EnsureApprovedVendor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
