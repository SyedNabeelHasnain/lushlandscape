<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\SecurityFilter;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SecurityHeaders::class,
            HandleRedirects::class,
            SecurityFilter::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->map(\InvalidArgumentException::class, function (\InvalidArgumentException $e) {
            if (request()->is('admin/page-builders/*')) {
                return new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($e->getMessage(), $e);
            }
            return $e;
        });
    })
    ->create();

$app->usePublicPath(dirname(__DIR__, 2).'/public_html');

return $app;
