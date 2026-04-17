<?php

namespace App\Services;

use App\Models\Examen;
use App\Models\Competencia;
use App\Models\Evaluacion;
use App\Repositories\ExamenRepository;
use App\Repositories\CompetidorRepository;
use App\Services\CalculadoraResultadosService;
use App\Exceptions\Dominio\ExamenException;
use App\Events\ExamenEstadoCambiado;
use Illuminate\Support\Facades\DB;

class ExamenService
{
    public function __construct(
        protected ExamenRepository $repository,
        protected CompetidorRepository $competidorRepo,
        protected CalculadoraResultadosService $calculadoraService
    ) {}

    public function crearExamen(array $data): Examen
    {
        return DB::transaction(function () use ($data) {
            $competencia = Competencia::findOrFail($data['id_competencia']);

            if ($competencia->estado_fase !== 'borrador') {
                throw new ExamenException('Solo se pueden agregar exámenes en etapa de borrador.');
            }

            $nuevaPonderacion = (float) $data['ponderacion'];
            $sumaActual       = $this->repository->sumarPonderaciones($competencia->id_competencia);
            $disponible       = 100.00 - $sumaActual;

            if (bccomp((string) $nuevaPonderacion, (string) ($disponible + 0.001), 4) > 0) {
                throw new ExamenException(
                    "La ponderación supera el 100%. Tienes ocupado el {$sumaActual}%, disponible: {$disponible}%."
                );
            }

            $examen = $this->repository->create($data);
            $this->generarFichasIniciales($examen, $competencia);

            return $examen;
        });
    }

    public function actualizarExamen(int $id, array $data): Examen
    {
        $examen = $this->repository->find($id);

        if ($examen->estado_ejecucion !== 'no_iniciada') {
            throw new ExamenException('No se puede editar un examen iniciado o finalizado.');
        }

        if (isset($data['ponderacion'])) {
            $nuevaPonderacion = (float) $data['ponderacion'];
            $sumaOtros        = $this->repository->sumarPonderaciones($examen->id_competencia, $id);
            $disponible       = 100.00 - $sumaOtros;

            if (bccomp((string) $nuevaPonderacion, (string) ($disponible + 0.001), 4) > 0) {
                throw new ExamenException(
                    "La ponderación supera el 100%. Solo sobran {$disponible}% disponibles para este examen."
                );
            }
        }

        $this->repository->update($data, $id);

        return $this->repository->find($id);
    }

    private function generarFichasIniciales(Examen $examen, Competencia $competencia): void
    {
        $competidores = $this->competidorRepo->getHabilitadosPorAreaNivel($competencia->id_area_nivel);

        if ($competidores->isEmpty()) {
            return;
        }

        $now    = now();
        $fichas = $competidores->map(fn ($c) => [
            'id_competidor'        => $c->id_competidor,
            'id_examen'            => $examen->id_examen,
            'nota'                 => 0.00,
            'estado_participacion' => 'presente',
            'esta_calificado'      => false,
            'bloqueado_por'        => null,
            'fecha_bloqueo'        => null,
            'created_at'           => $now,
            'updated_at'           => $now,
        ])->toArray();

        Evaluacion::insert($fichas);
    }

    public function iniciarExamen(int $id): Examen
    {
        $examen = $this->repository->find($id);

        $this->sincronizarCompetidoresFaltantes($id);

        if ($examen->evaluaciones()->count() === 0) {
            throw new ExamenException('No se puede iniciar el examen: no hay competidores habilitados inscritos.');
        }

        if ($examen->competencia->estado_fase !== 'en_proceso') {
            throw new ExamenException("La competencia debe estar 'en_proceso' para iniciar exámenes.");
        }

        if ($examen->estado_ejecucion !== 'no_iniciada') {
            throw new ExamenException('El examen ya fue iniciado.');
        }

        $this->repository->update([
            'estado_ejecucion'  => 'en_curso',
            'fecha_inicio_real' => now(),
        ], $id);

        $examen->refresh();
        broadcast(new ExamenEstadoCambiado($examen, 'en_curso'))->toOthers();

        return $examen;
    }

    public function finalizarExamen(int $id): Examen
    {
        return DB::transaction(function () use ($id) {
            $examen = Examen::lockForUpdate()->findOrFail($id);

            if ($examen->estado_ejecucion !== 'en_curso') {
                throw new ExamenException("Solo se puede finalizar un examen 'en_curso'.");
            }

            Evaluacion::where('id_examen', $id)
                ->whereNotNull('bloqueado_por')
                ->update(['bloqueado_por' => null, 'fecha_bloqueo' => null]);

            $examen->update(['estado_ejecucion' => 'finalizada']);

            $this->calculadoraService->procesarResultados($examen);

            broadcast(new ExamenEstadoCambiado($examen, 'finalizada'))->toOthers();

            return $examen;
        });
    }

    public function eliminarExamen(int $id): void
    {
        $examen = $this->repository->find($id);

        if ($examen->competencia->estado_fase !== 'borrador') {
            throw new ExamenException('No se puede eliminar exámenes de una competencia publicada.');
        }

        if ($examen->evaluaciones()->where('nota', '>', 0)->exists()) {
            throw new ExamenException('No se puede eliminar: ya existen registros de notas.');
        }

        $this->repository->delete($id);
    }

    public function sincronizarCompetidoresFaltantes(int $idExamen): int
    {
        $examen                  = $this->repository->find($idExamen);
        $competidoresHabilitados = $this->competidorRepo->getHabilitadosPorAreaNivel($examen->competencia->id_area_nivel);
        $idsConFicha             = $examen->evaluaciones()->pluck('id_competidor')->toArray();
        $now                     = now();
        $fichas                  = [];

        foreach ($competidoresHabilitados as $competidor) {
            if (!in_array($competidor->id_competidor, $idsConFicha)) {
                $fichas[] = [
                    'id_competidor'        => $competidor->id_competidor,
                    'id_examen'            => $examen->id_examen,
                    'nota'                 => 0.00,
                    'estado_participacion' => 'presente',
                    'esta_calificado'      => false,
                    'bloqueado_por'        => null,
                    'fecha_bloqueo'        => null,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
            }
        }

        if (!empty($fichas)) {
            Evaluacion::insert($fichas);
        }

        return count($fichas);
    }

    public function listarPorAreaNivel(int $idAreaNivel)
    {
        return $this->repository->getByAreaNivel($idAreaNivel);
    }

    public function listarParaCombo(int $idAreaNivel): array
    {
        return $this->repository->getSimpleByAreaNivel($idAreaNivel)
            ->map(fn ($e) => [
                'id_examen'     => $e->id_examen,
                'nombre_examen' => $e->nombre,
            ])->toArray();
    }

    public function listarCompetidores(int $idExamen): array
    {
        $this->repository->find($idExamen);

        return $this->repository->getCompetidoresDeExamen($idExamen)
            ->map(function ($eval) {
                $persona = $eval->competidor->persona;
                $grado   = $eval->competidor->gradoEscolaridad->nombre ?? 'Sin Grado';

                $estadoTexto = 'Sin calificar';
                if ($eval->esta_calificado) {
                    $estadoTexto = 'Calificado';
                } elseif ($eval->bloqueado_por !== null) {
                    $estadoTexto = 'Calificando';
                }

                return [
                    'id_evaluacion'     => $eval->id_evaluacion,
                    'id_competidor'     => $eval->id_competidor,
                    'ci'                => $persona->ci,
                    'nombre_completo'   => $persona->nombre . ' ' . $persona->apellido,
                    'grado_escolaridad' => $grado,
                    'estado_evaluacion' => $estadoTexto,
                    'nota_actual'       => $eval->nota,
                    'es_bloqueado'      => $eval->bloqueado_por !== null,
                    'bloqueado_por_mi'  => false,
                ];
            })->toArray();
    }
}
