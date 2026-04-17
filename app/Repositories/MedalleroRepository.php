<?php

namespace App\Repositories;

use App\Models\Olimpiada;
use App\Models\ParametroMedallero;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MedalleroRepository
{
    private function olimpiadaActiva(): ?Olimpiada
    {
        return Olimpiada::where('estado', true)->orderByDesc('gestion')->first();
    }

    public function getAreaPorResponsable(int $idResponsable): Collection
    {
        $olimpiada = $this->olimpiadaActiva();

        if (!$olimpiada) {
            return collect();
        }

        return DB::table('responsable_area AS ra')
            ->join('area_olimpiada AS ao', 'ra.id_area_olimpiada', '=', 'ao.id_area_olimpiada')
            ->join('area AS a', 'ao.id_area', '=', 'a.id_area')
            ->select('a.id_area', 'a.nombre AS nombre_area', DB::raw("'{$olimpiada->gestion}' as gestion"))
            ->where('ra.id_usuario', $idResponsable)
            ->where('ao.id_olimpiada', $olimpiada->id_olimpiada)
            ->distinct()
            ->orderBy('a.nombre')
            ->get();
    }

    public function getNivelesPorArea(int $idArea): Collection
    {
        $olimpiada = $this->olimpiadaActiva();

        if (!$olimpiada) {
            return collect();
        }

        return DB::table('area_nivel AS an')
            ->join('area_olimpiada AS ao', 'an.id_area_olimpiada', '=', 'ao.id_area_olimpiada')
            ->join('nivel AS n', 'an.id_nivel', '=', 'n.id_nivel')
            ->leftJoin('param_medallero AS pm', 'an.id_area_nivel', '=', 'pm.id_area_nivel')
            ->select(
                'an.id_area_nivel',
                'n.id_nivel',
                'n.nombre AS nombre_nivel',
                'pm.oro',
                'pm.plata',
                'pm.bronce',
                'pm.mencion'
            )
            ->where('ao.id_area', $idArea)
            ->where('ao.id_olimpiada', $olimpiada->id_olimpiada)
            ->where('an.es_activo', true)
            ->orderBy('n.id_nivel')
            ->get()
            ->map(function ($nivel) use ($olimpiada) {
                $nivel->gestion = $olimpiada->gestion;
                if ($nivel->oro === null) {
                    unset($nivel->oro, $nivel->plata, $nivel->bronce, $nivel->mencion);
                }
                return $nivel;
            });
    }

    public function insertarMedallero(array $niveles): array
    {
        $resultados = [];

        foreach ($niveles as $nivel) {
            ParametroMedallero::updateOrCreate(
                ['id_area_nivel' => $nivel['id_area_nivel']],
                [
                    'oro'     => $nivel['oro'],
                    'plata'   => $nivel['plata'],
                    'bronce'  => $nivel['bronce'],
                    'mencion' => $nivel['menciones'],
                ]
            );

            $resultados[] = [
                'id_area_nivel' => $nivel['id_area_nivel'],
                'mensaje'       => 'Guardado correctamente',
            ];
        }

        return $resultados;
    }
}
