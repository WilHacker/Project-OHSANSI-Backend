<?php

namespace App\Services\Sistema;

use App\Events\Sistema\SistemaEstadoActualizado;
use App\Repositories\Sistema\SistemaEstadoRepository;
use Carbon\Carbon;

class SistemaEstadoService
{
    public function __construct(
        private readonly SistemaEstadoRepository $repo
    ) {}

    public function obtenerSnapshotDelSistema(): array
    {
        $gestion = $this->repo->olimpiadaActiva();

        if (!$gestion) {
            return [
                'status'             => 'sin_gestion',
                'mensaje'            => 'No hay ninguna olimpiada activa configurada.',
                'server_timestamp'   => now()->toIso8601String(),
                'gestion_actual'     => null,
                'fase_global_activa' => null,
                'cronograma_vigente' => null,
            ];
        }

        $faseActiva = $this->repo->faseActivaConCronograma($gestion->id_olimpiada);

        $cronogramaInfo = null;
        if ($faseActiva) {
            $ahora  = Carbon::now();
            $inicio = Carbon::parse($faseActiva->fecha_inicio);
            $fin    = Carbon::parse($faseActiva->fecha_fin);

            $cronogramaInfo = [
                'fecha_inicio'   => $inicio->toIso8601String(),
                'fecha_fin'      => $fin->toIso8601String(),
                'en_fecha'       => $ahora->between($inicio, $fin),
                'dias_restantes' => $ahora->diffInDays($fin, false),
                'mensaje'        => $this->generarMensajeTiempo($ahora, $inicio, $fin),
            ];
        }

        return [
            'status'             => 'operativo',
            'server_timestamp'   => now()->toIso8601String(),
            'gestion_actual'     => [
                'id'      => $gestion->id_olimpiada,
                'nombre'  => $gestion->nombre,
                'gestion' => $gestion->gestion,
            ],
            'fase_global_activa' => $faseActiva ? [
                'id'     => $faseActiva->id_fase_global,
                'codigo' => $faseActiva->codigo,
                'nombre' => $faseActiva->nombre_fase,
                'orden'  => $faseActiva->orden,
            ] : null,
            'cronograma_vigente' => $cronogramaInfo,
        ];
    }

    public function difundirCambioDeEstado(): void
    {
        $snapshot = $this->obtenerSnapshotDelSistema();
        SistemaEstadoActualizado::dispatch($snapshot);
    }

    private function generarMensajeTiempo(Carbon $ahora, Carbon $inicio, Carbon $fin): string
    {
        if ($ahora->lt($inicio)) return 'Inicia ' . $inicio->diffForHumans($ahora);
        if ($ahora->gt($fin))   return 'Finalizó ' . $fin->diffForHumans($ahora);
        return 'En curso';
    }
}
