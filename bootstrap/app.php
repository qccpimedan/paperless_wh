<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
            api: __DIR__ . '/../routes/api.php',

        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', \App\Http\Middleware\SetPlantTimezone::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckForceLogout::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckMedanSubArea::class);
        $middleware->appendToGroup('api', \App\Http\Middleware\SetPlantTimezone::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Sesi telah diperbarui, silakan coba lagi.',
                    'csrf_token' => csrf_token()
                ], 419);
            }

            return redirect()->back()
                ->withInput($request->except('_token'))
                ->with('error', 'Halaman sempat tidak aktif (CSRF Expired). Data isian Anda telah kami selamatkan! Silakan tekan tombol Simpan sekali lagi.');
        });
    })->create();
