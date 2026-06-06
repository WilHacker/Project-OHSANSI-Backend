<?php

namespace App\Repositories\Configuracion;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermisoRepository
{
    public function esSuperAdmin(int $userId): bool
    {
        return DB::table('usuario_rol as ur')
            ->join('rol as r', 'ur.id_rol', '=', 'r.id_rol')
            ->where('ur.id_usuario', $userId)
            ->where(function ($query) {
                $query->where('r.id_rol', 1)
                      ->orWhere('r.nombre', 'Administrador');
            })
            ->exists();
    }

    public function tieneAccion(int $userId, string $accionCodigo): bool
    {
        return DB::table('usuario_rol as ur')
            ->join('rol_accion as ra', 'ur.id_rol', '=', 'ra.id_rol')
            ->join('accion_sistema as a', 'ra.id_accion_sistema', '=', 'a.id_accion_sistema')
            ->where('ur.id_usuario', $userId)
            ->where('a.codigo', $accionCodigo)
            ->where('ra.activo', true)
            ->exists();
    }

    public function cronogramaFaseActiva(int $idOlimpiada): ?object
    {
        return DB::table('cronograma_fase as cf')
            ->join('fase_global as fg', 'cf.id_fase_global', '=', 'fg.id_fase_global')
            ->where('fg.id_olimpiada', $idOlimpiada)
            ->where('cf.estado', 1)
            ->whereDate('cf.fecha_inicio', '<=', Carbon::now())
            ->whereDate('cf.fecha_fin', '>=', Carbon::now())
            ->select(
                'cf.id_fase_global',
                'fg.nombre as nombre_fase',
                'cf.fecha_inicio',
                'cf.fecha_fin'
            )
            ->first();
    }

    public function accionesPermitidasPorRoles(array $rolesIds, int $idFaseGlobal): array
    {
        return DB::table('accion_sistema as a')
            ->join('rol_accion as ra', 'a.id_accion_sistema', '=', 'ra.id_accion_sistema')
            ->join('configuracion_accion as ca', 'a.id_accion_sistema', '=', 'ca.id_accion_sistema')
            ->whereIn('ra.id_rol', $rolesIds)
            ->where('ra.activo', true)
            ->where('ca.id_fase_global', $idFaseGlobal)
            ->where('ca.habilitada', true)
            ->select('a.codigo', 'a.nombre', 'a.descripcion')
            ->distinct()
            ->get()
            ->toArray();
    }
}
