<?php

namespace App\Repositories;

use App\Models\Examen;
use App\Models\Evaluacion;
use Illuminate\Database\Eloquent\Collection;

class ExamenRepository
{
    public function find(int $id): Examen
    {
        return Examen::findOrFail($id);
    }

    public function create(array $data): Examen
    {
        return Examen::create($data);
    }

    public function update(array $data, int $id): bool
    {
        return $this->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->find($id)->delete();
    }

    public function getByAreaNivel(int $idAreaNivel): Collection
    {
        return Examen::select([
            'id_examen', 'id_competencia', 'nombre', 'ponderacion', 'maxima_nota',
            'fecha_hora_inicio', 'tipo_regla', 'configuracion_reglas', 'estado_ejecucion', 'fecha_inicio_real',
        ])
        ->whereHas('competencia', function ($q) use ($idAreaNivel) {
            $q->where('id_area_nivel', $idAreaNivel)
              ->whereHas('areaNivel.areaOlimpiada.olimpiada', fn ($qO) => $qO->where('estado', true));
        })
        ->get();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Examen> */
    public function getSimpleByAreaNivel(int $idAreaNivel): Collection
    {
        return Examen::select('id_examen', 'nombre')
            ->whereHas('competencia', function ($q) use ($idAreaNivel) {
                $q->where('id_area_nivel', $idAreaNivel)
                  ->whereHas('areaNivel.areaOlimpiada.olimpiada', fn ($qO) => $qO->where('estado', true));
            })
            ->get();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Evaluacion> */
    public function getCompetidoresDeExamen(int $idExamen): Collection
    {
        return Evaluacion::where('id_examen', $idExamen)
            ->with([
                'competidor.persona',
                'competidor.gradoEscolaridad',
                'usuarioBloqueo.persona',
            ])
            ->get();
    }

    public function paginadosPorCompetencia(int $competenciaId, int $porPagina = 15)
    {
        return Examen::where('id_competencia', $competenciaId)
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);
    }

    public function sumarPonderaciones(int $competenciaId, ?int $excludeId = null): float
    {
        return (float) Examen::where('id_competencia', $competenciaId)
            ->when($excludeId, fn ($q) => $q->where('id_examen', '!=', $excludeId))
            ->sum('ponderacion');
    }
}
