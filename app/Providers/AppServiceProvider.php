<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios en el contenedor.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arrancar servicios de la aplicación.
     */
    public function boot(): void
    {
        $this->configurarLimitesDeVelocidad();
    }

    /**
     * Definir los limitadores de velocidad para la API.
     *
     * - login: protege contra ataques de fuerza bruta al endpoint de autenticación.
     * - api: límite general para rutas autenticadas.
     */
    private function configurarLimitesDeVelocidad(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $maxIntentos = (int) env('RATE_LIMIT_LOGIN', 10);

            return Limit::perMinute($maxIntentos)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'mensaje' => 'Demasiados intentos de inicio de sesión. Intente de nuevo en un minuto.',
                    ], 429);
                });
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)
                ->by($request->user()?->id_usuario ?: $request->ip());
        });
    }
}
