<?php

namespace App\Repositories;

use App\Models\Medallero;
use App\Models\Evaluacion;
use App\Models\LogCambioNota;
use App\Models\Area;
use App\Models\Nivel;
use App\Models\Competencia;
use App\Models\ResponsableArea;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReporteRepository
{
    public function getMedallero(int $idCompetencia)
    {
        return Medallero::where('id_competencia', $idCompetencia)
            ->with(['competidor.persona', 'competidor.institucion'])
            ->orderBy('puesto')
            ->get()
            ->map(fn ($item) => [
                'puesto'      => $item->puesto,
                'medalla'     => $item->medalla,
                'nombre'      => $this->nombreCompleto($item->competidor->persona),
                'institucion' => $item->competidor->institucion->nombre,
            ]);
    }

    public function getClasificados(int $idCompetencia)
    {
        return Evaluacion::whereHas('examen', fn ($q) => $q->where('id_competencia', $idCompetencia))
            ->where('resultado_calculado', 'CLASIFICADO')
            ->with(['competidor.persona', 'competidor.institucion', 'examen'])
            ->get()
            ->map(fn ($item) => [
                'examen'      => $item->examen->nombre,
                'nombre'      => $this->nombreCompleto($item->competidor->persona),
                'institucion' => $item->competidor->institucion->nombre,
                'nota'        => $item->nota,
                'resultado'   => $item->resultado_calculado,
            ]);
    }

    public function getLogCambios(int $idEvaluacion)
    {
        return LogCambioNota::where('id_evaluacion', $idEvaluacion)
            ->with('autor.persona')
            ->orderByDesc('fecha_cambio')
            ->get()
            ->map(fn ($log) => [
                'fecha'         => $log->fecha_cambio->format('d/m/Y H:i'),
                'autor'         => $this->nombreCompleto($log->autor->persona),
                'nota_anterior' => $log->nota_anterior,
                'nota_nueva'    => $log->nota_nueva,
                'motivo'        => $log->motivo_cambio,
            ]);
    }

    public function getHistorialCompleto(int $page, int $limit, array $filtros): array
    {
        $query = LogCambioNota::query()
            ->with([
                'autor.persona',
                'evaluacion.competidor.persona',
                'evaluacion.competidor.institucion',
                'evaluacion.competidor.gradoEscolaridad',
                'evaluacion.examen.competencia.areaNivel.area',
                'evaluacion.examen.competencia.areaNivel.nivel',
            ])
            ->whereHas('evaluacion.examen.competencia.areaNivel.areaOlimpiada.olimpiada', fn ($q) => $q->where('estado', true));

        if (!empty($filtros['id_area'])) {
            $query->whereHas('evaluacion.examen.competencia.areaNivel.areaOlimpiada', function ($q) use ($filtros) {
                $q->where('id_area', $filtros['id_area']);
            });
        }

        if (!empty($filtros['ids_niveles'])) {
            $ids = is_array($filtros['ids_niveles']) ? $filtros['ids_niveles'] : explode(',', $filtros['ids_niveles']);
            $query->whereHas('evaluacion.examen.competencia.areaNivel', fn ($q) => $q->whereIn('id_nivel', $ids));
        }

        if (!empty($filtros['search'])) {
            $term = $filtros['search'];
            $query->where(function ($subQ) use ($term) {
                $subQ->whereHas('autor.persona', function ($q) use ($term) {
                    $q->where('nombre', 'LIKE', "%{$term}%")
                      ->orWhere('apellido', 'LIKE', "%{$term}%");
                })
                ->orWhereHas('evaluacion.competidor.persona', function ($q) use ($term) {
                    $q->where('nombre', 'LIKE', "%{$term}%")
                      ->orWhere('apellido', 'LIKE', "%{$term}%");
                });
            });
        }

        $paginator = $query->orderByDesc('fecha_cambio')->paginate($limit, ['*'], 'page', $page);

        $items = $paginator->getCollection()->map(function ($log) {
            $areaNivel = $log->evaluacion->examen->competencia->areaNivel ?? null;
            $diff      = $log->nota_nueva - $log->nota_anterior;
            [$accion, $tipoCambio] = $this->determinarAccion($log->nota_anterior, $log->nota_nueva);

            return [
                'id_historial'      => $log->id_log_cambio_nota,
                'fecha_hora'        => Carbon::parse($log->fecha_cambio)->toIso8601String(),
                'nombre_evaluador'  => $this->nombreCompleto($log->autor->persona ?? null),
                'nombre_olimpista'  => $this->nombreCompleto($log->evaluacion->competidor->persona ?? null),
                'institucion'       => $log->evaluacion->competidor->institucion->nombre ?? 'Sin Institución',
                'area'              => $areaNivel->area->nombre ?? 'N/A',
                'nivel'             => $areaNivel->nivel->nombre ?? 'N/A',
                'grado_escolaridad' => $log->evaluacion->competidor->gradoEscolaridad->nombre ?? 'N/A',
                'id_area'           => $areaNivel->id_area ?? null,
                'id_nivel'          => $areaNivel->id_nivel ?? null,
                'accion'            => $accion,
                'tipo_cambio'       => $tipoCambio,
                'nota_anterior'     => (float) $log->nota_anterior,
                'nota_nueva'        => (float) $log->nota_nueva,
                'diferencia'        => (float) $diff,
                'observacion'       => $log->motivo_cambio,
                'descripcion'       => $this->generarFrase($accion, $log->nota_anterior, $log->nota_nueva),
            ];
        });

        return [
            'success' => true,
            'data'    => $items,
            'meta'    => [
                'total'      => $paginator->total(),
                'page'       => $paginator->currentPage(),
                'limit'      => $paginator->perPage(),
                'totalPages' => $paginator->lastPage(),
            ],
        ];
    }

    public function getAreasActivas()
    {
        return Area::whereHas('areaOlimpiadas.olimpiada', fn ($q) => $q->where('estado', true))
            ->select('id_area', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    public function getNivelesActivosPorArea(int $idArea)
    {
        return Nivel::whereHas('areaNiveles', function ($q) use ($idArea) {
            $q->whereHas('areaOlimpiada', function ($qAo) use ($idArea) {
                $qAo->where('id_area', $idArea)
                    ->whereHas('olimpiada', fn ($q2) => $q2->where('estado', true));
            });
        })
        ->select('id_nivel', 'nombre')
        ->orderBy('nombre')
        ->get();
    }

    public function getDatosExportMedallero(int $idCompetencia): Collection
    {
        $competencia = Competencia::with([
            'areaNivel.areaOlimpiada.area',
            'areaNivel.nivel',
            'areaNivel.areaOlimpiada',
        ])->findOrFail($idCompetencia);

        $areaNivel   = $competencia->areaNivel;
        $areaNombre  = $areaNivel?->areaOlimpiada?->area?->nombre ?? 'N/A';
        $nivelNombre = $areaNivel?->nivel?->nombre ?? 'N/A';

        $responsableNombre = $this->obtenerNombreResponsable(
            $areaNivel?->id_area_olimpiada ?? 0
        );

        return Medallero::where('id_competencia', $idCompetencia)
            ->with([
                'competidor.persona',
                'competidor.institucion',
                'competidor.departamento',
            ])
            ->orderBy('puesto')
            ->get()
            ->map(fn ($item) => [
                'nombre_completo'  => $this->nombreCompleto($item->competidor?->persona),
                'unidad_educativa' => $item->competidor?->institucion?->nombre ?? '',
                'departamento'     => $item->competidor?->departamento?->nombre ?? '',
                'area'             => $areaNombre,
                'nivel'            => $nivelNombre,
                'nota'             => (float) ($item->competidor?->evaluaciones
                    ->where('id_examen', fn ($e) => true)->first()?->nota ?? 0),
                'posicion'         => $item->puesto,
                'medalla'          => $item->medalla,
                'tutor_academico'  => $item->competidor?->tutor_academico ?? '',
                'responsable_area' => $responsableNombre,
            ]);
    }

    public function getDatosExportClasificados(int $idCompetencia): Collection
    {
        $competencia = Competencia::with([
            'areaNivel.areaOlimpiada.area',
            'areaNivel.nivel',
        ])->findOrFail($idCompetencia);

        $areaNivel   = $competencia->areaNivel;
        $areaNombre  = $areaNivel?->areaOlimpiada?->area?->nombre ?? 'N/A';
        $nivelNombre = $areaNivel?->nivel?->nombre ?? 'N/A';

        return Evaluacion::whereHas('examen', fn ($q) => $q->where('id_competencia', $idCompetencia))
            ->with([
                'competidor.persona',
                'competidor.institucion',
                'competidor.departamento',
            ])
            ->get()
            ->map(fn ($item) => [
                'nombre_completo' => $this->nombreCompleto($item->competidor?->persona),
                'institucion'     => $item->competidor?->institucion?->nombre ?? '',
                'departamento'    => $item->competidor?->departamento?->nombre ?? '',
                'area'            => $areaNombre,
                'nivel'           => $nivelNombre,
                'nota'            => (float) $item->nota,
                'resultado'       => $item->resultado_calculado ?? '',
                'observacion'     => $item->observacion ?? '',
            ])
            ->sortByDesc('nota')
            ->values();
    }

    private function obtenerNombreResponsable(int $idAreaOlimpiada): string
    {
        $responsable = ResponsableArea::where('id_area_olimpiada', $idAreaOlimpiada)
            ->with('usuario.persona')
            ->latest()
            ->first();

        return $this->nombreCompleto($responsable?->usuario?->persona);
    }

    private function determinarAccion($ant, $nue): array
    {
        if ($ant == 0 && $nue > 0) return ['Calificar', 'CREATE'];
        if ($nue == 0 && $ant > 0) return ['Desclasificar', 'DELETE'];
        return ['Modificar', 'UPDATE'];
    }

    private function nombreCompleto($persona): string
    {
        return $persona ? "{$persona->nombre} {$persona->apellido}" : 'Desconocido';
    }

    private function generarFrase(string $accion, $ant, $nue): string
    {
        return match ($accion) {
            'Calificar'     => "Calificación inicial asignada: {$nue} pts.",
            'Desclasificar' => "Participante desclasificado. Nota anulada.",
            default         => "Nota modificada de {$ant} a {$nue} pts.",
        };
    }
}
