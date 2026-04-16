<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\EvaluacionService;
use App\Repositories\EvaluacionRepository;
use App\Model\Evaluacion;
use App\Model\Examen;
use App\Exceptions\Dominio\EvaluacionException;
use App\Exceptions\Dominio\AutorizacionException;
use Illuminate\Support\Facades\DB;
use Mockery;

class EvaluacionServiceTest extends TestCase
{
    private EvaluacionRepository $repo;
    private EvaluacionService $servicio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo    = Mockery::mock(EvaluacionRepository::class);
        $this->servicio = new EvaluacionService($this->repo);
    }

    // ─── bloquearFicha ─────────────────────────────────────────────────────────

    public function test_bloquear_ficha_cuando_examen_no_esta_en_curso_lanza_excepcion(): void
    {
        $this->expectException(EvaluacionException::class);
        $this->expectExceptionMessageMatches('/en curso/');

        $examen               = new Examen();
        $examen->estado_ejecucion = 'pendiente';

        $evaluacion          = new Evaluacion();
        $evaluacion->setRelation('examen', $examen);
        $evaluacion->bloqueado_por = null;

        $this->repo->shouldReceive('findForUpdate')->with(1)->andReturn($evaluacion)->once();

        // bloquearFicha usa DB::transaction — lo hacemos pasar sin base de datos
        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($cb) => $cb());

        $this->servicio->bloquearFicha(1, 99);
    }

    public function test_bloquear_ficha_bloqueada_por_otro_juez_reciente_lanza_excepcion(): void
    {
        $this->expectException(EvaluacionException::class);
        $this->expectExceptionMessageMatches('/Ficha ocupada/');

        $examen                   = new Examen();
        $examen->estado_ejecucion = 'en_curso';

        $persona        = new \stdClass();
        $persona->nombre = 'María';

        $usuarioBloqueo = Mockery::mock();
        $usuarioBloqueo->persona = $persona;

        $evaluacion                = new Evaluacion();
        $evaluacion->setRelation('examen', $examen);
        $evaluacion->setRelation('usuarioBloqueo', $usuarioBloqueo);
        $evaluacion->bloqueado_por  = 5;  // otro juez
        $evaluacion->fecha_bloqueo  = now()->subMinute(1)->toDateTimeString(); // hace 1 minuto (< 5)

        $this->repo->shouldReceive('findForUpdate')->with(1)->andReturn($evaluacion)->once();

        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($cb) => $cb());

        $this->servicio->bloquearFicha(1, 99); // juez 99 intenta tomar ficha del juez 5
    }

    // ─── guardarNota ───────────────────────────────────────────────────────────

    public function test_guardar_nota_sin_ser_el_bloqueador_lanza_excepcion(): void
    {
        $this->expectException(EvaluacionException::class);
        $this->expectExceptionMessageMatches('/bloqueo/');

        $evaluacion              = new Evaluacion();
        $evaluacion->bloqueado_por = 5;  // bloqueado por juez 5
        $evaluacion->esta_calificado = false;

        $this->repo->shouldReceive('findForUpdate')->with(10)->andReturn($evaluacion)->once();

        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($cb) => $cb());

        $this->servicio->guardarNota(10, [
            'user_id'              => 99, // juez 99 intenta guardar
            'nota'                 => 85.0,
            'estado_participacion' => 'presente',
        ]);
    }

    // ─── descalificarCompetidor ─────────────────────────────────────────────────

    public function test_descalificar_sin_bloqueo_previo_lanza_excepcion(): void
    {
        $this->expectException(AutorizacionException::class);
        $this->expectExceptionMessageMatches('/bloquear/');

        $evaluacion              = new Evaluacion();
        $evaluacion->bloqueado_por = null; // nadie bloqueó

        $this->repo->shouldReceive('findForUpdate')->with(10)->andReturn($evaluacion)->once();

        DB::shouldReceive('transaction')->once()->andReturnUsing(fn ($cb) => $cb());

        $this->servicio->descalificarCompetidor(10, 99, 'Uso de celular');
    }

    // ─── desbloquearFicha ──────────────────────────────────────────────────────

    public function test_desbloquear_ficha_de_otro_juez_lanza_excepcion(): void
    {
        $this->expectException(AutorizacionException::class);
        $this->expectExceptionMessageMatches('/otro juez/');

        $examen                    = new Examen();
        $evaluacion                = new Evaluacion();
        $evaluacion->bloqueado_por = 5; // bloqueado por juez 5
        $evaluacion->setRelation('examen', $examen);

        $this->repo->shouldReceive('find')->with(10)->andReturn($evaluacion)->once();

        $this->servicio->desbloquearFicha(10, 99); // juez 99 intenta desbloquear
    }
}
