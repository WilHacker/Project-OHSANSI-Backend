<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CompetenciaService;
use App\Repositories\CompetenciaRepository;
use App\Repositories\UsuarioRepository;
use App\Models\Competencia;
use App\Exceptions\Dominio\CompetenciaException;
use App\Exceptions\Dominio\AutorizacionException;
use Mockery;

class CompetenciaServiceTest extends TestCase
{
    private CompetenciaRepository $repo;
    private UsuarioRepository $usuarioRepo;
    private CompetenciaService $servicio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo        = Mockery::mock(CompetenciaRepository::class);
        $this->usuarioRepo = Mockery::mock(UsuarioRepository::class);
        $this->servicio    = new CompetenciaService($this->repo, $this->usuarioRepo);
    }

    // ─── actualizar ────────────────────────────────────────────────────────────

    public function test_actualizar_competencia_en_borrador_es_exitoso(): void
    {
        $competencia                = new Competencia();
        $competencia->estado_fase   = 'borrador';
        $competenciaActualizada     = new Competencia();

        $this->repo->shouldReceive('find')->with(1)->andReturn($competencia)->twice();
        $this->repo->shouldReceive('update')->with(['fecha_inicio' => '2026-05-01'], 1)->once();

        $resultado = $this->servicio->actualizar(1, ['fecha_inicio' => '2026-05-01']);

        $this->assertInstanceOf(Competencia::class, $resultado);
    }

    public function test_actualizar_competencia_publicada_lanza_excepcion(): void
    {
        $this->expectException(CompetenciaException::class);
        $this->expectExceptionMessageMatches('/borrador/');

        $competencia              = new Competencia();
        $competencia->estado_fase = 'publicada';

        $this->repo->shouldReceive('find')->with(1)->andReturn($competencia)->once();

        $this->servicio->actualizar(1, ['fecha_inicio' => '2026-05-01']);
    }

    // ─── eliminar ──────────────────────────────────────────────────────────────

    public function test_eliminar_competencia_publicada_lanza_excepcion(): void
    {
        $this->expectException(CompetenciaException::class);

        $competencia              = new Competencia();
        $competencia->estado_fase = 'publicada';

        $this->repo->shouldReceive('find')->with(5)->andReturn($competencia)->once();

        $this->servicio->eliminar(5);
    }

    public function test_eliminar_competencia_en_borrador_es_exitoso(): void
    {
        $competencia              = new Competencia();
        $competencia->estado_fase = 'borrador';

        $this->repo->shouldReceive('find')->with(5)->andReturn($competencia)->once();
        $this->repo->shouldReceive('delete')->with(5)->once();

        $this->servicio->eliminar(5);
        $this->addToAssertionCount(1); // Sin excepción = éxito
    }

    // ─── publicar ──────────────────────────────────────────────────────────────

    public function test_publicar_competencia_sin_examenes_lanza_excepcion(): void
    {
        $this->expectException(CompetenciaException::class);
        $this->expectExceptionMessageMatches('/exámenes/');

        $examenesRelacion = Mockery::mock(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        $examenesRelacion->shouldReceive('count')->andReturn(0);

        $competencia              = Mockery::mock(Competencia::class)->makePartial();
        $competencia->estado_fase = 'borrador';
        $competencia->shouldReceive('examenes')->andReturn($examenesRelacion);

        $this->repo->shouldReceive('find')->with(3)->andReturn($competencia)->once();

        $this->servicio->publicar(3);
    }

    public function test_publicar_competencia_no_borrador_lanza_excepcion(): void
    {
        $this->expectException(CompetenciaException::class);
        $this->expectExceptionMessageMatches('/borrador/');

        $competencia              = new Competencia();
        $competencia->estado_fase = 'publicada';

        $this->repo->shouldReceive('find')->with(3)->andReturn($competencia)->once();

        $this->servicio->publicar(3);
    }

    // ─── iniciar ───────────────────────────────────────────────────────────────

    public function test_iniciar_competencia_no_publicada_lanza_excepcion(): void
    {
        $this->expectException(CompetenciaException::class);
        $this->expectExceptionMessageMatches('/publicada/');

        $competencia              = new Competencia();
        $competencia->estado_fase = 'borrador';

        $this->repo->shouldReceive('find')->with(7)->andReturn($competencia)->once();

        $this->servicio->iniciar(7);
    }

    // ─── listarPorResponsableYArea ─────────────────────────────────────────────

    public function test_listar_por_responsable_sin_rol_lanza_excepcion(): void
    {
        $this->expectException(AutorizacionException::class);

        $this->usuarioRepo->shouldReceive('tieneRol')
            ->with(42, 'Responsable de Area')
            ->andReturn(false)
            ->once();

        $this->servicio->listarPorResponsableYArea(42, 1);
    }
}
