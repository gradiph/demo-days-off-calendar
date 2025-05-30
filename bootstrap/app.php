<?php

use App\Console\Commands\DispatchSlackStatusUpdate;
use App\Jobs\UpdateSlackStatus;
use Illuminate\Console\Scheduling\Schedule;
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
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(new DispatchSlackStatusUpdate)->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
