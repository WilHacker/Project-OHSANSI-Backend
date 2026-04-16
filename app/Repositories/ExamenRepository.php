<?php

namespace App\Repositories;

use App\Model\Examen;
use App\Model\Evaluacion;
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

    public function getSimpleByAreaNivel(int $idAreaNivel): Collection
    {
        return Examen::select('id_examen', 'nombre')
            ->whereHas('competencia', function ($q) use ($idAreaNivel) {
                $q->where('id_area_nivel', $idAreaNivel)
                  ->whereHas('areaNivel.areaOlimpiada.olimpiada', fn ($qO) => $qO->where('estado', true));
            })
            ->get();
    }

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

    public function sumarPonderaciones(int $competenciaId, ?int $excludeId = null): float
    {
        return (float) Examen::where('id_competencia', $competenciaId)
            ->when($excludeId, fn ($q) => $q->where('id_examen', '!=', $excludeId))
            ->sum('ponderacion');
    }
}
