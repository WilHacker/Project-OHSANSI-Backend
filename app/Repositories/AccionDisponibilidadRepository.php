<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AccionDisponibilidadRepository
{
    public function obtenerAccionesHabilitadas(int $idRol, int $idFaseGlobal, int $idOlimpiada): Collection
    {
        return DB::table('accion_sistema as a')
            // Corregido: id_accion -> id_accion_sistema
            ->join('rol_accion as ra', 'a.id_accion_sistema', '=', 'ra.id_accion_sistema')
            ->join('configuracion_accion as ca', 'a.id_accion_sistema', '=', 'ca.id_accion_sistema')
            // Nota: id_olimpiada NO existe en configuracion_accion según tu migración.
            // Si necesitas filtrar por olimpiada, debes hacerlo a través de la relación con fase_global.
            ->where('ra.id_rol', $idRol)
            ->where('ra.activo', 1)
            ->where('ca.id_fase_global', $idFaseGlobal)
            ->where('ca.habilitada', 1)
            ->select([
                'a.id_accion_sistema', // Corregido
                'a.codigo',
                'a.nombre',
                'a.descripcion'
            ])
            ->distinct()
            ->get();
    }
}
