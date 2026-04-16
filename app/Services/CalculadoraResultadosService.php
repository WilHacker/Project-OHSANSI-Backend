<?php

namespace App\Services;

use App\Model\Evaluacion;
use App\Model\Examen;
use App\Model\Parametro;

class CalculadoraResultadosService
{
    /**
     * Punto de entrada: ejecuta el cálculo automático al cerrar un examen.
     *
     * El $examen debe llegar con 'evaluaciones' y 'competencia' ya cargados
     * para evitar queries adicionales.
     */
    public function procesarResultados(Examen $examen): void
    {
        if ($examen->evaluaciones->isEmpty()) {
            return;
        }

        match ($examen->tipo_regla) {
            'nota_corte' => $this->aplicarNotaCorte($examen),
            default      => $this->logicaPorDefecto($examen),
        };
    }

    /**
     * ESTRATEGIA: Nota de Corte (Filtro).
     *
     * Clasifica a cada competidor como CLASIFICADO o NO CLASIFICADO
     * según si supera la nota mínima. Ejecuta un solo UPDATE por lote
     * en lugar de N queries individuales.
     */
    private function aplicarNotaCorte(Examen $examen): void
    {
        $notaMinima = $this->obtenerNotaMinima($examen);

        // Acumulamos los resultados para actualizar en un solo batch
        $resultados = [];

        foreach ($examen->evaluaciones as $evaluacion) {
            $resultado = match ($evaluacion->estado_participacion) {
                'ausente'             => 'REPROBADO (Ausente)',
                'descalificado_etica' => 'DESCALIFICADO',
                'presente'            => ($evaluacion->nota >= $notaMinima) ? 'CLASIFICADO' : 'NO CLASIFICADO',
                default               => 'NO CLASIFICADO',
            };

            $resultados[] = [
                'id_evaluacion'      => $evaluacion->id_evaluacion,
                'resultado_calculado' => $resultado,
            ];
        }

        $this->actualizarResultadosEnLote($resultados);
    }

    /**
     * Lógica por defecto para exámenes sumativos (sin regla de corte).
     * Marca como COMPLETADO a los presentes en un solo batch.
     */
    private function logicaPorDefecto(Examen $examen): void
    {
        $resultados = [];

        foreach ($examen->evaluaciones as $evaluacion) {
            if ($evaluacion->estado_participacion === 'presente') {
                $resultados[] = [
                    'id_evaluacion'      => $evaluacion->id_evaluacion,
                    'resultado_calculado' => 'COMPLETADO',
                ];
            }
        }

        $this->actualizarResultadosEnLote($resultados);
    }

    /**
     * Actualiza resultado_calculado en un solo batch usando upsert.
     *
     * Reemplaza el antipatrón de llamar $evaluacion->update() N veces
     * en un loop. Ahora se emite una sola consulta SQL por bloque.
     *
     * @param  array<int, array{id_evaluacion: int, resultado_calculado: string}>  $resultados
     */
    private function actualizarResultadosEnLote(array $resultados): void
    {
        if (empty($resultados)) {
            return;
        }

        Evaluacion::upsert(
            $resultados,
            uniqueBy: ['id_evaluacion'],
            update: ['resultado_calculado']
        );
    }

    /**
     * Obtiene la nota mínima priorizando:
     * 1. Configuración local del examen (JSON configuracion_reglas).
     * 2. Parámetro global de la olimpiada para el área-nivel.
     * 3. Valor predeterminado: 51.0
     *
     * Asume que $examen->competencia ya está cargado (eager loading).
     */
    private function obtenerNotaMinima(Examen $examen): float
    {
        $config = $examen->configuracion_reglas;

        if (isset($config['nota_minima']) && is_numeric($config['nota_minima'])) {
            return (float) $config['nota_minima'];
        }

        $competencia = $examen->competencia;

        if ($competencia && $competencia->id_area_nivel) {
            $parametro = Parametro::where('id_area_nivel', $competencia->id_area_nivel)->first();

            if ($parametro && !is_null($parametro->nota_min_aprobacion)) {
                return (float) $parametro->nota_min_aprobacion;
            }
        }

        return 51.0;
    }
}
