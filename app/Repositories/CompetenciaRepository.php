<?php

namespace App\Repositories;

use App\Models\Competencia;
use App\Models\AreaNivel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CompetenciaRepository
{
    public function find(int $id): Competencia
    {
        return Competencia::findOrFail($id);
    }

    public function findWithFullHierarchy(int $id): Competencia
    {
        return Competencia::with([
            'examenes.evaluaciones',
            'areaNivel',
        ])->findOrFail($id);
    }

    public function create(array $data): Competencia
    {
        return Competencia::create($data);
    }

    public function update(array $data, int $id): bool
    {
        return $this->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->find($id)->delete();
    }

    public function getAll(int $porPagina = 15): LengthAwarePaginator
    {
        return Competencia::with(['faseGlobal', 'areaNivel.areaOlimpiada.area', 'areaNivel.nivel'])
            ->orderByDesc('fecha_inicio')
            ->paginate($porPagina);
    }

    public function getByResponsableAndArea(int $idResponsable, int $idArea): Collection
    {
        return Competencia::select([
            'id_competencia', 'id_fase_global', 'id_area_nivel',
            'fecha_inicio', 'fecha_fin', 'estado_fase', 'criterio_clasificacion',
            'id_usuario_aval', 'fecha_aval',
        ])
        ->whereHas('areaNivel.areaOlimpiada', function ($q) use ($idArea, $idResponsable) {
            $q->where('id_area', $idArea)
              ->whereHas('responsableArea', fn ($qR) => $qR->where('id_usuario', $idResponsable))
              ->whereHas('olimpiada', fn ($qO) => $qO->where('estado', true));
        })
        ->with(['areaNivel.nivel'])
        ->orderByDesc('fecha_inicio')
        ->get()
        ->map(fn ($c) => [
            'id_competencia'         => $c->id_competencia,
            'id_fase_global'         => $c->id_fase_global,
            'id_area_nivel'          => $c->id_area_nivel,
            'nivel'                  => $c->areaNivel->nivel->nombre ?? 'Desconocido',
            'fecha_inicio'           => $c->fecha_inicio,
            'fecha_fin'              => $c->fecha_fin,
            'estado_fase'            => $c->estado_fase,
            'criterio_clasificacion' => $c->criterio_clasificacion,
            'id_usuario_aval'        => $c->id_usuario_aval,
            'fecha_aval'             => $c->fecha_aval,
        ]);
    }

    public function getNivelesPorAreaActual(int $idArea): Collection
    {
        return AreaNivel::whereHas('areaOlimpiada', function ($q) use ($idArea) {
            $q->where('id_area', $idArea)
              ->whereHas('olimpiada', fn ($qO) => $qO->where('estado', true));
        })
        ->with('nivel')
        ->get()
        ->map(fn ($item) => [
            'id_area_nivel' => $item->id_area_nivel,
            'nivel'         => $item->nivel->nombre,
        ]);
    }

    public function getActivasPorResponsable(int $idResponsable): Collection
    {
        return Competencia::whereHas('areaNivel.areaOlimpiada', function ($q) use ($idResponsable) {
            $q->whereHas('responsableArea', fn ($qR) => $qR->where('id_usuario', $idResponsable))
              ->whereHas('olimpiada', fn ($qO) => $qO->where('estado', true));
        })
        ->with(['areaNivel.nivel', 'areaNivel.areaOlimpiada.area'])
        ->get();
    }
}
