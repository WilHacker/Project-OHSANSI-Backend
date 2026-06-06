<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Persona> */
class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    public function definition(): array
    {
        return [
            'nombre'   => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'ci'       => fake()->unique()->numerify('########'),
            'telefono' => fake()->unique()->numerify('7#######'),
            'email'    => fake()->unique()->safeEmail(),
        ];
    }
}
