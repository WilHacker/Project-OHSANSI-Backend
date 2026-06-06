<?php

namespace Tests\Feature\Evaluacion;

use App\Models\Competidor;
use App\Models\Evaluacion;
use App\Models\Examen;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EvaluacionIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    private Usuario $juez;
    private Examen $examen;
    private Evaluacion $evaluacion;

    protected function setUp(): void
    {
        parent::setUp();
        config(['broadcasting.default' => 'log']);

        $this->juez = Usuario::factory()->create();

        $this->examen = Examen::factory()->enCurso()->create();

        $competidor = Competidor::factory()->create([
            'id_area_nivel' => $this->examen->competencia->id_area_nivel,
        ]);

        $this->evaluacion = Evaluacion::factory()->create([
            'id_examen'     => $this->examen->id_examen,
            'id_competidor' => $competidor->id_competidor,
        ]);
    }

    public function test_bloquear_ficha_de_examen_en_curso_guarda_bloqueo_en_bd(): void
    {
        $respuesta = $this->actingAs($this->juez, 'sanctum')
            ->postJson("/api/v1/sala-evaluacion/{$this->evaluacion->id_evaluacion}/bloquear");

        $respuesta->assertOk()
            ->assertJsonPath('datos.bloqueo.usuario_id', $this->juez->id_usuario);

        $this->assertDatabaseHas('evaluacion', [
            'id_evaluacion' => $this->evaluacion->id_evaluacion,
            'bloqueado_por' => $this->juez->id_usuario,
        ]);
    }

    public function test_desbloquear_ficha_propia_limpia_el_bloqueo_en_bd(): void
    {
        // Primero bloqueamos
        Evaluacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)->update([
            'bloqueado_por' => $this->juez->id_usuario,
            'fecha_bloqueo' => now(),
        ]);

        $respuesta = $this->actingAs($this->juez, 'sanctum')
            ->postJson("/api/v1/sala-evaluacion/{$this->evaluacion->id_evaluacion}/desbloquear");

        $respuesta->assertOk()
            ->assertJsonPath('mensaje', 'Ficha liberada.');

        $this->assertDatabaseHas('evaluacion', [
            'id_evaluacion' => $this->evaluacion->id_evaluacion,
            'bloqueado_por' => null,
        ]);
    }

    public function test_guardar_nota_actualiza_en_bd(): void
    {
        // Primero bloqueamos la ficha
        Evaluacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)->update([
            'bloqueado_por' => $this->juez->id_usuario,
            'fecha_bloqueo' => now(),
        ]);

        $respuesta = $this->actingAs($this->juez, 'sanctum')
            ->postJson("/api/v1/sala-evaluacion/{$this->evaluacion->id_evaluacion}/guardar", [
                'nota'                 => 85.5,
                'estado_participacion' => 'presente',
            ]);

        $respuesta->assertOk()
            ->assertJsonPath('datos.calificado', true);

        $this->assertDatabaseHas('evaluacion', [
            'id_evaluacion'  => $this->evaluacion->id_evaluacion,
            'esta_calificado' => true,
        ]);
    }

    public function test_bloquear_ficha_examen_no_en_curso_da_error(): void
    {
        $examenPendiente = Examen::factory()->create(['estado_ejecucion' => 'no_iniciada']);
        $evaluacionPendiente = Evaluacion::factory()->create([
            'id_examen' => $examenPendiente->id_examen,
        ]);

        $respuesta = $this->actingAs($this->juez, 'sanctum')
            ->postJson("/api/v1/sala-evaluacion/{$evaluacionPendiente->id_evaluacion}/bloquear");

        $respuesta->assertStatus(422);
    }

    public function test_desbloquear_ficha_de_otro_juez_da_403(): void
    {
        $otroJuez = Usuario::factory()->create();

        Evaluacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)->update([
            'bloqueado_por' => $otroJuez->id_usuario,
            'fecha_bloqueo' => now(),
        ]);

        $respuesta = $this->actingAs($this->juez, 'sanctum')
            ->postJson("/api/v1/sala-evaluacion/{$this->evaluacion->id_evaluacion}/desbloquear");

        $respuesta->assertForbidden();
    }
}
