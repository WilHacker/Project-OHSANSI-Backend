<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

use App\Repositories\CompetenciaRepository;
use App\Repositories\EvaluacionRepository;
use App\Repositories\ExamenRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\Interfaces\CompetenciaRepositoryInterface;
use App\Repositories\Interfaces\EvaluacionRepositoryInterface;
use App\Repositories\Interfaces\ExamenRepositoryInterface;
use App\Repositories\Interfaces\UsuarioRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompetenciaRepositoryInterface::class, CompetenciaRepository::class);
        $this->app->bind(EvaluacionRepositoryInterface::class,  EvaluacionRepository::class);
        $this->app->bind(ExamenRepositoryInterface::class,      ExamenRepository::class);
        $this->app->bind(UsuarioRepositoryInterface::class,     UsuarioRepository::class);
    }

    public function boot(): void
    {
        $this->configurarLimitesDeVelocidad();
    }

    private function configurarLimitesDeVelocidad(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $maxIntentos = config('ohsansi.rate_limit.login', 10);

            return Limit::perMinute($maxIntentos)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'mensaje' => 'Demasiados intentos de inicio de sesión. Intente de nuevo en un minuto.',
                    ], 429);
                });
        });

        RateLimiter::for('api', function (Request $request) {
            $maxApi = config('ohsansi.rate_limit.api', 120);

            return Limit::perMinute($maxApi)
                ->by($request->user()?->id_usuario ?: $request->ip());
        });
    }
}
