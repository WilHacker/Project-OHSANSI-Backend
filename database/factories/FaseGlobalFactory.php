<?php

namespace Database\Factories;

use App\Models\FaseGlobal;
use App\Models\Olimpiada;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FaseGlobal> */
class FaseGlobalFactory extends Factory
{
    protected $model = FaseGlobal::class;

    public function definition(): array
    {
        return [
            'id_olimpiada' => Olimpiada::factory(),
            'codigo'       => fake()->randomElement(['CONFIGURACION', 'EVALUACION', 'FINAL']),
            'nombre'       => fake()->randomElement(['Clasificatoria', 'Departamental', 'Nacional']),
            'orden'        => fake()->numberBetween(1, 3),
        ];
    }
}
