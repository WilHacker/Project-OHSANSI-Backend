<?php

namespace Database\Factories;

use App\Models\GradoEscolaridad;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GradoEscolaridad> */
class GradoEscolaridadFactory extends Factory
{
    protected $model = GradoEscolaridad::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                '1ro de Primaria', '2do de Primaria', '3ro de Primaria',
                '4to de Primaria', '5to de Primaria', '6to de Primaria',
                '1ro de Secundaria', '2do de Secundaria', '3ro de Secundaria',
                '4to de Secundaria', '5to de Secundaria', '6to de Secundaria',
            ]),
        ];
    }
}
