<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Services\UsuarioService;
use Illuminate\Support\Facades\RateLimiter;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Limpiar rate limiter antes de cada test
        RateLimiter::clear('login');
    }

    public function test_login_exitoso_devuelve_token(): void
    {
        $this->mock(UsuarioService::class, function ($mock) {
            $mock->shouldReceive('login')
                ->once()
                ->with(['email' => 'juez@ohsansi.bo', 'password' => 'secreto'])
                ->andReturn([
                    'access_token' => 'tok_123',
                    'token_type'   => 'Bearer',
                    'user'         => [
                        'id_usuario' => 1,
                        'nombre'     => 'Juan',
                        'apellido'   => 'Pérez',
                        'email'      => 'juez@ohsansi.bo',
                        'roles'      => ['Evaluador'],
                    ],
                ]);
        });

        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'juez@ohsansi.bo',
            'password' => 'secreto',
        ]);

        $respuesta->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'user'])
            ->assertJsonPath('token_type', 'Bearer');
    }

    public function test_login_con_credenciales_incorrectas_devuelve_401(): void
    {
        $this->mock(UsuarioService::class, function ($mock) {
            $mock->shouldReceive('login')->once()->andReturn(null);
        });

        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'noexiste@ohsansi.bo',
            'password' => 'equivocada',
        ]);

        $respuesta->assertUnauthorized()
            ->assertJsonPath('message', 'Credenciales no autorizadas');
    }

    public function test_login_con_campos_faltantes_devuelve_422(): void
    {
        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email' => 'juez@ohsansi.bo',
            // falta password
        ]);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_con_email_invalido_devuelve_422(): void
    {
        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'no-es-un-email',
            'password' => 'secreto',
        ]);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_excede_rate_limit_devuelve_429(): void
    {
        $this->mock(UsuarioService::class, function ($mock) {
            $mock->shouldReceive('login')->andReturn(null);
        });

        $limite = (int) env('RATE_LIMIT_LOGIN', 10);

        // Agota el límite
        for ($i = 0; $i < $limite; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email'    => 'juez@ohsansi.bo',
                'password' => 'mal',
            ]);
        }

        // El siguiente debe ser bloqueado
        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'juez@ohsansi.bo',
            'password' => 'mal',
        ]);

        $respuesta->assertStatus(429);
    }

    public function test_ruta_protegida_sin_token_devuelve_401(): void
    {
        $respuesta = $this->getJson('/api/v1/auth/me');

        $respuesta->assertUnauthorized();
    }
}
