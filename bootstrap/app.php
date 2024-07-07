<?php

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\MalformedApiResponseException;
use App\Exceptions\ModelNotSavedException;
use App\Exceptions\NotVerifiedException;
use App\Utils\DispatchBatchedExchangeRateNotificationJobs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {

        $schedule->call(new DispatchBatchedExchangeRateNotificationJobs)->dailyAt('05:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AlreadyExistsException $e) {
            return response(null, 409)->header('Content-Type', 'application/json');
        });
        $exceptions->render(function (MalformedApiResponseException $e) {
            return response(null, 400)->header('Content-Type', 'application/json');
        });
        $exceptions->render(function (ValidationException $e) {
            return response(null, 400)->header('Content-Type', 'application/json');
        });
        $exceptions->render(function (ConnectionException $e) {
            return response(null, 400)->header('Content-Type', 'application/json');
        });
        $exceptions->render(function (ModelNotSavedException $e) {
            return response(null, 500)->header('Content-Type', 'application/json');
        });
        $exceptions->render(function (InvalidSignatureException $e) {
            return response()->view('auth/email-error-verified', [], 403);
        });
        $exceptions->render(function (NotVerifiedException $e) {
            return response(null, 500)->header('Content-Type', 'application/json');
        });
    })->create();
