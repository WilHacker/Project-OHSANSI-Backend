<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Evaluacion */
class EvaluacionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id_evaluacion,
            'examen_id'            => $this->id_examen,
            'competidor_id'        => $this->id_competidor,
            'nota'                 => $this->nota !== null ? (float) $this->nota : null,
            'estado_participacion' => $this->estado_participacion,
            'resultado'            => $this->resultado_calculado,
            'calificado'           => (bool) $this->esta_calificado,
            'observacion'          => $this->observacion,
            'bloqueo'              => [
                'usuario_id' => $this->bloqueado_por,
                'desde'      => $this->fecha_bloqueo?->toIso8601String(),
            ],
        ];
    }
}
