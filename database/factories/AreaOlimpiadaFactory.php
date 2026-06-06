<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\AreaOlimpiada;
use App\Models\Olimpiada;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AreaOlimpiada> */
class AreaOlimpiadaFactory extends Factory
{
    protected $model = AreaOlimpiada::class;

    public function definition(): array
    {
        return [
            'id_area'      => Area::factory(),
            'id_olimpiada' => Olimpiada::factory(),
        ];
    }
}
