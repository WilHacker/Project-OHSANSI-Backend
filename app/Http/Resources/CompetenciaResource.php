<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Competencia */
class CompetenciaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id_competencia,
            'fase_global_id'         => $this->id_fase_global,
            'area_nivel_id'          => $this->id_area_nivel,
            'fecha_inicio'           => $this->fecha_inicio->toDateString(),
            'fecha_fin'              => $this->fecha_fin->toDateString(),
            'estado_fase'            => $this->estado_fase,
            'criterio_clasificacion' => $this->criterio_clasificacion,
            'avalada'                => !is_null($this->id_usuario_aval),
            'fecha_aval'             => $this->fecha_aval?->toIso8601String(),
            'examenes'               => $this->whenLoaded('examenes', fn () => ExamenResource::collection($this->examenes)),
        ];
    }
}
