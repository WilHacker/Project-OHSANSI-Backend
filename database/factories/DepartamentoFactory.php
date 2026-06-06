<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Departamento> */
class DepartamentoFactory extends Factory
{
    protected $model = Departamento::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'La Paz', 'Cochabamba', 'Santa Cruz', 'Oruro',
                'Potosí', 'Chuquisaca', 'Tarija', 'Beni', 'Pando',
            ]),
        ];
    }
}
