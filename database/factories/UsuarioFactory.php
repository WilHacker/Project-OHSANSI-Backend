<?php

namespace Database\Factories;

use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/** @extends Factory<Usuario> */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'id_persona' => Persona::factory(),
            'email'      => fake()->unique()->safeEmail(),
            'password'   => Hash::make('password'),
        ];
    }

    public function conPassword(string $password): static
    {
        return $this->state(['password' => Hash::make($password)]);
    }
}
