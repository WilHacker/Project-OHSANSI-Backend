<?php

namespace Tests\Feature\Evaluacion;

use Tests\TestCase;
use App\Models\Usuario;
use App\Services\EvaluacionService;
use App\Models\Evaluacion;
use App\Models\Examen;
use App\Exceptions\Dominio\EvaluacionException;
use App\Exceptions\Dominio\AutorizacionException;
use Mockery;

class EvaluacionTest extends TestCase
{
    private function usuarioAutenticado(int $id = 1): Usuario
    {
        $usuario             = Mockery::mock(Usuario::class)->makePartial();
        $usuario->id_usuario = $id;
        return $usuario;
    }

    // ─── autenticación ─────────────────────────────────────────────────────────

    public function test_bloquear_sin_token_da_401(): void
    {
        $respuesta = $this->postJson('/api/v1/sala-evaluacion/1/bloquear');
        $respuesta->assertUnauthorized();
    }

    public function test_guardar_nota_sin_token_da_401(): void
    {
        $respuesta = $this->postJson('/api/v1/sala-evaluacion/1/guardar');
        $respuesta->assertUnauthorized();
    }

    public function test_desbloquear_sin_token_da_401(): void
    {
        $respuesta = $this->postJson('/api/v1/sala-evaluacion/1/desbloquear');
        $respuesta->assertUnauthorized();
    }

    // ─── bloquear ──────────────────────────────────────────────────────────────

    public function test_bloquear_ficha_con_examen_no_en_curso_da_409(): void
    {
        $this->mock(EvaluacionService::class, function ($mock) {
            $mock->shouldReceive('bloquearFicha')
                ->with(1, 1)
                ->andThrow(new EvaluacionException('El examen no está en curso, no se puede bloquear.', 409));
        });

        $usuario   = $this->usuarioAutenticado(1);
        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->postJson('/api/v1/sala-evaluacion/1/bloquear');

        $respuesta->assertStatus(409)
            ->assertJsonPath('mensaje', 'El examen no está en curso, no se puede bloquear.');
    }

    public function test_bloquear_ficha_ocupada_por_otro_juez_da_409(): void
    {
        $this->mock(EvaluacionService::class, function ($mock) {
            $mock->shouldReceive('bloquearFicha')
                ->with(5, 1)
                ->andThrow(new EvaluacionException('Ficha ocupada por María. Intente en unos instantes.', 409));
        });

        $usuario   = $this->usuarioAutenticado(1);
        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->postJson('/api/v1/sala-evaluacion/5/bloquear');

        $respuesta->assertStatus(409)
            ->assertJsonPath('mensaje', 'Ficha ocupada por María. Intente en unos instantes.');
    }

    // ─── guardar nota ──────────────────────────────────────────────────────────

    public function test_bloquear_exitoso_llama_al_servicio(): void
    {
        $examen                   = new Examen();
        $examen->id_examen        = 10;
        $examen->estado_ejecucion = 'en_curso';

        $evaluacion                = new Evaluacion();
        $evaluacion->id_evaluacion = 1;
        $evaluacion->bloqueado_por = 1;
        $evaluacion->setRelation('examen', $examen);

        $this->mock(EvaluacionService::class, function ($mock) use ($evaluacion) {
            $mock->shouldReceive('bloquearFicha')->with(1, 1)->once()->andReturn($evaluacion);
        });

        $usuario   = $this->usuarioAutenticado(1);
        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->postJson('/api/v1/sala-evaluacion/1/bloquear');

        $respuesta->assertOk();
    }

    public function test_desbloquear_exitoso_llama_al_servicio(): void
    {
        $examen    = new Examen();
        $evaluacion = new Evaluacion();
        $evaluacion->bloqueado_por = null;
        $evaluacion->setRelation('examen', $examen);

        $this->mock(EvaluacionService::class, function ($mock) use ($evaluacion) {
            $mock->shouldReceive('desbloquearFicha')->with(1, 1)->once()->andReturn($evaluacion);
        });

        $usuario   = $this->usuarioAutenticado(1);
        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->postJson('/api/v1/sala-evaluacion/1/desbloquear');

        $respuesta->assertOk();
    }

    // ─── desbloquear ───────────────────────────────────────────────────────────

    public function test_desbloquear_ficha_de_otro_juez_da_403(): void
    {
        $this->mock(EvaluacionService::class, function ($mock) {
            $mock->shouldReceive('desbloquearFicha')
                ->andThrow(new AutorizacionException('No puedes desbloquear una ficha que pertenece a otro juez.'));
        });

        $usuario   = $this->usuarioAutenticado(2);
        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->postJson('/api/v1/sala-evaluacion/1/desbloquear');

        $respuesta->assertForbidden()
            ->assertJsonPath('mensaje', 'No puedes desbloquear una ficha que pertenece a otro juez.');
    }
}
