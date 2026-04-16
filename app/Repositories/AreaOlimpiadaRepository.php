<?php

namespace App\Repositories;

use App\Model\Area;
use Illuminate\Support\Collection;

class AreaOlimpiadaRepository
{
    public function findAreasByOlimpiadaId(int $idOlimpiada): Collection
    {
        return Area::join('area_olimpiada', 'area.id_area', '=', 'area_olimpiada.id_area')
            ->where('area_olimpiada.id_olimpiada', $idOlimpiada)
            ->select('area.id_area', 'area.nombre')
            ->get();
    }

    public function findAreasByGestion(string $gestion): Collection
    {
        return Area::join('area_olimpiada', 'area.id_area', '=', 'area_olimpiada.id_area')
            ->join('olimpiada', 'area_olimpiada.id_olimpiada', '=', 'olimpiada.id_olimpiada')
            ->where('olimpiada.gestion', $gestion)
            ->select('area.id_area', 'area.nombre')
            ->get();
    }

    public function findNombresAreasByGestion(string $gestion): Collection
    {
        return $this->findAreasByGestion($gestion)->pluck('nombre');
    }

    public function findAreasByOlimpiadaAndResponsable(int $idOlimpiada, int $idResponsable): Collection
    {
        return Area::join('area_olimpiada', 'area.id_area', '=', 'area_olimpiada.id_area')
            ->join('responsable_area', 'area_olimpiada.id_area_olimpiada', '=', 'responsable_area.id_area_olimpiada')
            ->where('area_olimpiada.id_olimpiada', $idOlimpiada)
            ->where('responsable_area.id_usuario', $idResponsable)
            ->select('area.id_area', 'area.nombre')
            ->distinct()
            ->orderBy('area.nombre')
            ->get();
    }
}
