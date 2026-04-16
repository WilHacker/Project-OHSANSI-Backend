<?php

namespace App\Repositories;

use App\Model\Area;
use Illuminate\Database\Eloquent\Collection;

class AreaRepository
{
    public function getAllAreas(): Collection
    {
        return Area::orderBy('nombre')->get();
    }

    public function createArea(array $data): Area
    {
        return Area::create($data);
    }

    public function getAreasByGestion(string $gestion): Collection
    {
        return Area::whereHas('olimpiadas', fn ($q) => $q->where('gestion', $gestion))
            ->select('id_area', 'nombre')
            ->orderBy('nombre')
            ->get();
    }

    public function getByResponsableActual(int $idUsuario): Collection
    {
        return Area::select('id_area', 'nombre')
            ->whereHas('areaOlimpiadas', function ($q) use ($idUsuario) {
                $q->whereHas('olimpiada', fn ($qO) => $qO->where('estado', true))
                  ->whereHas('responsableArea', fn ($qR) => $qR->where('id_usuario', $idUsuario));
            })
            ->orderBy('nombre')
            ->get();
    }
}
