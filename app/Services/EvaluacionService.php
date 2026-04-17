<?php

namespace App\Services;

use App\Exceptions\Dominio\AutorizacionException;
use App\Exceptions\Dominio\EvaluacionException;
use App\Repositories\EvaluacionRepository;
use App\Models\Examen;
use App\Events\CompetidorBloqueado;
use App\Events\CompetidorLiberado;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvaluacionService
{
    public function __construct(
        protected EvaluacionRepository $repo
    ) {}

    public function obtenerPizarraExamen(int $idExamen)
    {
        return Examen::with([
            'evaluaciones.competidor.persona',
            'evaluaciones.usuarioBloqueo.persona',
        ])->findOrFail($idExamen);
    }

    public function bloquearFicha(int $idEvaluacion, int $userId)
    {
        return DB::transaction(function () use ($idEvaluacion, $userId) {
            $evaluacion = $this->repo->findForUpdate($idEvaluacion);

            if ($evaluacion->examen->estado_ejecucion !== 'en_curso') {
                throw new EvaluacionException('El examen no está en curso, no se puede bloquear.', 422);
            }

            if ($evaluacion->bloqueado_por !== null && $evaluacion->bloqueado_por !== $userId) {
                $timeoutMinutos = config('ohsansi.bloqueo_timeout_minutos', 5);
                $horaBloqueo    = Carbon::parse($evaluacion->fecha_bloqueo);

                if (now()->diffInMinutes($horaBloqueo) < $timeoutMinutos) {
                    $nombreJuez = $evaluacion->usuarioBloqueo?->persona?->nombre ?? 'otro juez';
                    throw new EvaluacionException("Ficha ocupada por {$nombreJuez}. Intente en unos instantes.");
                }
            }

            $evaluacion = $this->repo->bloquear($evaluacion, $userId);
            $evaluacion->load('examen');
            broadcast(new CompetidorBloqueado($evaluacion))->toOthers();

            return $evaluacion;
        });
    }

    public function guardarNota(int $idEvaluacion, array $datos)
    {
        return DB::transaction(function () use ($idEvaluacion, $datos) {
            $evaluacion = $this->repo->findForUpdate($idEvaluacion);

            if ($evaluacion->bloqueado_por === null) {
                throw new EvaluacionException('La ficha no está bloqueada. Debes bloquearla primero.', 422);
            }

            if ($evaluacion->bloqueado_por !== $datos['user_id']) {
                throw new EvaluacionException('Perdiste el bloqueo de esta ficha.', 422);
            }

            if ($evaluacion->esta_calificado && $evaluacion->nota != $datos['nota']) {
                if (empty($datos['motivo_cambio'])) {
                    throw new EvaluacionException('El motivo es obligatorio al corregir una calificación.', 422);
                }

                $this->repo->registrarLog([
                    'id_evaluacion'    => $idEvaluacion,
                    'id_usuario_autor' => $datos['user_id'],
                    'nota_anterior'    => $evaluacion->nota,
                    'nota_nueva'       => $datos['nota'],
                    'motivo_cambio'    => $datos['motivo_cambio'],
                ]);
            }

            $evaluacion = $this->repo->updateNota($evaluacion, [
                'nota'                 => $datos['nota'],
                'estado_participacion' => $datos['estado_participacion'],
                'observacion'          => $datos['observacion'] ?? null,
            ]);

            $evaluacion->load('examen');
            broadcast(new CompetidorLiberado($evaluacion, $datos['nota']))->toOthers();

            return $evaluacion;
        });
    }

    public function descalificarCompetidor(int $idEvaluacion, int $userId, string $motivo)
    {
        return DB::transaction(function () use ($idEvaluacion, $userId, $motivo) {
            $evaluacion = $this->repo->findForUpdate($idEvaluacion);

            if ($evaluacion->bloqueado_por === null) {
                throw new AutorizacionException('Debes bloquear la ficha antes de descalificar.');
            }

            if ($evaluacion->bloqueado_por !== $userId) {
                throw new AutorizacionException('Debes bloquear la ficha antes de descalificar.');
            }

            $this->repo->registrarLog([
                'id_evaluacion'    => $idEvaluacion,
                'id_usuario_autor' => $userId,
                'nota_anterior'    => $evaluacion->nota,
                'nota_nueva'       => 0,
                'motivo_cambio'    => "DESCALIFICACIÓN: {$motivo}",
            ]);

            $evaluacion = $this->repo->descalificar($evaluacion, $motivo);
            $evaluacion->load('examen');
            broadcast(new CompetidorLiberado($evaluacion, 0))->toOthers();

            return $evaluacion;
        });
    }

    public function desbloquearFicha(int $idEvaluacion, int $userId)
    {
        $evaluacion = $this->repo->find($idEvaluacion);

        if ($evaluacion->bloqueado_por !== null && $evaluacion->bloqueado_por !== $userId) {
            throw new AutorizacionException('No puedes desbloquear una ficha que pertenece a otro juez.');
        }

        $evaluacion = $this->repo->desbloquear($evaluacion);
        $evaluacion->load('examen');
        broadcast(new CompetidorLiberado($evaluacion))->toOthers();

        return $evaluacion;
    }

    public function listarAreasNivelesParaEvaluador(int $userId): array
    {
        $asignaciones = $this->repo->getAreasConExamenesPorEvaluador($userId);

        if ($asignaciones->isEmpty()) {
            return [];
        }

        $agrupado  = $asignaciones->groupBy(fn ($item) => $item->areaNivel->areaOlimpiada->area->id_area);
        $resultado = [];

        foreach ($agrupado as $idArea => $items) {
            $primero = $items->first();

            $niveles = $items->map(fn ($asignacion) => [
                'id_area_nivel'      => $asignacion->id_area_nivel,
                'id_area_nivel_real' => $asignacion->areaNivel->id_area_nivel,
                'nombre_nivel'       => $asignacion->areaNivel->nivel->nombre,
            ])->values()->toArray();

            $resultado[] = [
                'id_area' => $idArea,
                'area'    => $primero->areaNivel->areaOlimpiada->area->nombre,
                'niveles' => $niveles,
            ];
        }

        return $resultado;
    }
}
