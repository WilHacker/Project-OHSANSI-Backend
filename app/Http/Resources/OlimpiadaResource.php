<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Olimpiada */
class OlimpiadaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id_olimpiada,
            'nombre'  => $this->nombre,
            'gestion' => $this->gestion,
            'activa'  => (bool) $this->estado,
        ];
    }
}
