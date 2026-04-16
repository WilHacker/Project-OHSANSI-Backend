<?php

namespace Tests\Feature\Competencia;

use Tests\TestCase;
use App\Model\Usuario;
use App\Services\CompetenciaService;
use App\Exceptions\Dominio\CompetenciaException;
use Mockery;

class MaquinaEstadosTest extends TestCase
{
    private function usuarioAutenticado(): Usuario
    {
        $usuario = Mockery::mock(Usuario::class)->makePartial();
        $usuario->id_usuario = 1;
        // Sanctum acepta cualquier usuario que implemente Authenticatable
        return $usuario;
    }

    // ─── publicar ──────────────────────────────────────────────────────────────

    public function test_publicar_competencia_en_borrador_sin_examenes_da_422(): void
    {
        $this->mock(CompetenciaService::class, function ($mock) {
            $mock->shouldReceive('publicar')
                ->with(1)
                ->andThrow(new CompetenciaException('No puedes publicar una competencia sin exámenes configurados.'));
        });

        $usuario = $this->usuarioAutenticado();

        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->patchJson('/api/v1/competencias/1/publicar');

        $respuesta->assertStatus(422)
            ->assertJsonPath('mensaje', 'No puedes publicar una competencia sin exámenes configurados.');
    }

    public function test_publicar_competencia_ya_publicada_da_422(): void
    {
        $this->mock(CompetenciaService::class, function ($mock) {
            $mock->shouldReceive('publicar')
                ->with(2)
                ->andThrow(new CompetenciaException('La competencia ya no está en borrador y no puede publicarse nuevamente.'));
        });

        $usuario = $this->usuarioAutenticado();

        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->patchJson('/api/v1/competencias/2/publicar');

        $respuesta->assertStatus(422)
            ->assertJsonPath('mensaje', 'La competencia ya no está en borrador y no puede publicarse nuevamente.');
    }

    // ─── iniciar ───────────────────────────────────────────────────────────────

    public function test_iniciar_competencia_en_borrador_sin_publicar_da_422(): void
    {
        $this->mock(CompetenciaService::class, function ($mock) {
            $mock->shouldReceive('iniciar')
                ->with(1)
                ->andThrow(new CompetenciaException(
                    "La competencia debe estar en estado 'publicada' para poder iniciarse. Estado actual: borrador."
                ));
        });

        $usuario = $this->usuarioAutenticado();

        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->patchJson('/api/v1/competencias/1/iniciar');

        $respuesta->assertStatus(422)
            ->assertJsonStructure(['mensaje']);
    }

    // ─── autenticación ─────────────────────────────────────────────────────────

    public function test_publicar_sin_autenticacion_da_401(): void
    {
        $respuesta = $this->patchJson('/api/v1/competencias/1/publicar');

        $respuesta->assertUnauthorized();
    }

    public function test_iniciar_sin_autenticacion_da_401(): void
    {
        $respuesta = $this->patchJson('/api/v1/competencias/1/iniciar');

        $respuesta->assertUnauthorized();
    }
}
