<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Examen */
class ExamenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id_examen,
            'competencia_id'    => $this->id_competencia,
            'nombre'            => $this->nombre,
            'ponderacion'       => (float) $this->ponderacion,
            'maxima_nota'       => (float) $this->maxima_nota,
            'fecha_hora_inicio' => $this->fecha_hora_inicio?->toIso8601String(),
            'tipo_regla'        => $this->tipo_regla,
            'reglas'            => $this->configuracion_reglas,
            'estado'            => $this->estado_ejecucion,
            'iniciado_en'       => $this->fecha_inicio_real?->toIso8601String(),
        ];
    }
}
