<?php

namespace App\Repositories\Sistema;

use App\Models\Olimpiada;
use Illuminate\Support\Facades\DB;

class SistemaEstadoRepository
{
    public function olimpiadaActiva(): ?Olimpiada
    {
        return Olimpiada::where('estado', 1)->first();
    }

    public function faseActivaConCronograma(int $idOlimpiada): ?object
    {
        return DB::table('fase_global as fg')
            ->join('cronograma_fase as cf', 'fg.id_fase_global', '=', 'cf.id_fase_global')
            ->where('fg.id_olimpiada', $idOlimpiada)
            ->where('cf.estado', 1)
            ->select([
                'fg.id_fase_global',
                'fg.codigo',
                'fg.nombre as nombre_fase',
                'fg.orden',
                'cf.fecha_inicio',
                'cf.fecha_fin',
                'cf.id_cronograma_fase',
            ])
            ->first();
    }
}
