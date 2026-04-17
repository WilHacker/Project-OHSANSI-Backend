<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccionSistema;
use App\Models\FaseGlobal;
use App\Models\ConfiguracionAccion;

class ConfiguracionAccionSeeder extends Seeder
{
    /**
     * Qué acciones están habilitadas por defecto en cada fase.
     * Clave: código de la fase. Valor: array de códigos de acción habilitados.
     */
    private array $habilitadasPorFase = [
        'CONFIGURACION' => [
            'DASHBOARD',
            'OLIMPIADAS',
            'CRONOGRAMA',
            'AREAS',
            'NIVELES',
            'ASIGNACIONES',
            'RESPONSABLES',
            'EVALUADORES',
            'PARAMETROS',
            'ACTIVIDADES_FASES',
            'GESTIONAR_ROLES',
            'REPORTES_CAMBIOS',
        ],
        'EVALUACION' => [
            'DASHBOARD',
            'OLIMPIADAS',
            'CRONOGRAMA',
            'AREAS',
            'NIVELES',
            'ASIGNACIONES',
            'RESPONSABLES',
            'EVALUADORES',
            'INSCRIPCION',
            'COMPETIDORES',
            'COMPETENCIAS',
            'EXAMENES',
            'SALA_EVALUACION',
            'GESTIONAR_ROLES',
            'REPORTES_CAMBIOS',
        ],
        'FINAL' => [
            'DASHBOARD',
            'OLIMPIADAS',
            'CRONOGRAMA',
            'AREAS',
            'NIVELES',
            'ASIGNACIONES',
            'RESPONSABLES',
            'EVALUADORES',
            'COMPETIDORES',
            'COMPETENCIAS',
            'EXAMENES',
            'SALA_EVALUACION',
            'MEDALLERO',
            'GESTIONAR_ROLES',
            'REPORTES_CAMBIOS',
        ],
    ];

    public function run(): void
    {
        $acciones = AccionSistema::all()->keyBy('codigo');
        $fases    = FaseGlobal::all()->keyBy('codigo');

        if ($acciones->isEmpty()) {
            $this->command->warn('⚠️  No hay acciones en la BD. Ejecuta AccionSistemaSeeder primero.');
            return;
        }

        if ($fases->isEmpty()) {
            $this->command->warn('⚠️  No hay fases en la BD. Ejecuta FaseGlobalSeeder primero.');
            return;
        }

        $total = 0;

        foreach ($fases as $codigoFase => $fase) {
            $codigosHabilitados = $this->habilitadasPorFase[$codigoFase] ?? [];

            foreach ($acciones as $codigoAccion => $accion) {
                $habilitada = in_array($codigoAccion, $codigosHabilitados);

                ConfiguracionAccion::updateOrCreate(
                    [
                        'id_accion_sistema' => $accion->id_accion_sistema,
                        'id_fase_global'    => $fase->id_fase_global,
                    ],
                    [
                        'habilitada' => $habilitada,
                    ]
                );

                $total++;
            }

            $count = count($codigosHabilitados);
            $this->command->info("  Fase [{$codigoFase}]: {$count} acciones habilitadas.");
        }

        $this->command->info("✅ ConfiguracionAccion: {$total} entradas sincronizadas.");
    }
}
