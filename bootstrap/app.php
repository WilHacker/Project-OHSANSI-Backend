<?php

use App\Exceptions\AppException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        /*
        |----------------------------------------------------------------------
        | CORS
        |----------------------------------------------------------------------
        | Se antepone a todas las rutas para que las peticiones OPTIONS
        | (preflight) sean respondidas antes de llegar a los middlewares
        | de autenticación.
        */
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);

        /*
        |----------------------------------------------------------------------
        | Throttle general para la API
        |----------------------------------------------------------------------
        | El limitador 'api' está definido en AppServiceProvider.
        | El limitador 'login' se aplica directamente en routes/api.php
        | solo al endpoint de autenticación.
        */
        $middleware->throttleApi('api');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*
        |----------------------------------------------------------------------
        | Forzar respuesta JSON en todas las rutas de la API
        |----------------------------------------------------------------------
        */
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        /*
        |----------------------------------------------------------------------
        | Manejo centralizado de excepciones de dominio
        |----------------------------------------------------------------------
        | Las excepciones que extienden AppException son lanzadas desde los
        | Services y se transforman aquí en respuestas JSON con el código
        | HTTP adecuado. Los controladores NO necesitan try/catch para éstas.
        */
        $exceptions->render(function (AppException $e, Request $request) {
            return response()->json([
                'mensaje' => $e->getMessage(),
            ], $e->getCodigoHttp());
        });
    })->create();
