<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Di lingkungan Vercel serverless, arahkan path storage dan bootstrap cache ke /tmp (writable)
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || env('APP_ENV') === 'production') {
    $storagePath = '/tmp/storage';
    if (! is_dir($storagePath)) {
        @mkdir($storagePath, 0755, true);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
