<?php

namespace App\Repositories;

use App\Models\AreaNivel;
use App\Models\AreaOlimpiada;
use Illuminate\Database\Eloquent\Collection;

class AreaNivelRepository
{
    public function getAllAreasNiveles(): Collection
    {
        return AreaNivel::with(['areaOlimpiada.area', 'nivel', 'areaOlimpiada.olimpiada'])->get();
    }

    public function getByArea(int $id_area, int $id_olimpiada): Collection
    {
        return AreaNivel::whereHas('areaOlimpiada', function ($q) use ($id_area, $id_olimpiada) {
            $q->where('id_area', $id_area)
              ->where('id_olimpiada', $id_olimpiada);
        })->with(['nivel', 'areaOlimpiada'])->get();
    }

    public function getById(int $id): ?AreaNivel
    {
        return AreaNivel::with(['areaOlimpiada.area', 'nivel', 'areaOlimpiada.olimpiada'])->find($id);
    }

    public function create(array $data): AreaNivel
    {
        return AreaNivel::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return AreaNivel::findOrFail($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) AreaNivel::findOrFail($id)->delete();
    }

    public function getByAreaAndNivel(int $id_area_olimpiada, int $id_nivel): ?AreaNivel
    {
        return AreaNivel::where('id_area_olimpiada', $id_area_olimpiada)
            ->where('id_nivel', $id_nivel)
            ->first();
    }

    public function getByAreaOlimpiada(int $id_area_olimpiada): Collection
    {
        return AreaNivel::where('id_area_olimpiada', $id_area_olimpiada)
            ->with(['nivel', 'gradosEscolaridad'])
            ->get();
    }
}
