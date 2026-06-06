<?php

namespace Database\Factories;

use App\Models\AreaNivel;
use App\Models\AreaOlimpiada;
use App\Models\Nivel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AreaNivel> */
class AreaNivelFactory extends Factory
{
    protected $model = AreaNivel::class;

    public function definition(): array
    {
        return [
            'id_area_olimpiada' => AreaOlimpiada::factory(),
            'id_nivel'          => Nivel::factory(),
            'es_activo'         => true,
        ];
    }
}
