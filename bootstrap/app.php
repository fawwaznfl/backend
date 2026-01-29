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
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ FIX auth API (tidak redirect ke login)
        $middleware->redirectGuestsTo(function () {
            return null;
        });

        // ✅ Alias middleware custom
        $middleware->alias([
            'role.dashboard' => \App\Http\Middleware\RoleDashboardMiddleware::class,
            'company.scope'  => \App\Http\Middleware\CompanyScopeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
