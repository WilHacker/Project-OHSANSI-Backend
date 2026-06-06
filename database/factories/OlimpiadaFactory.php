<?php

namespace Database\Factories;

use App\Models\Olimpiada;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Olimpiada> */
class OlimpiadaFactory extends Factory
{
    protected $model = Olimpiada::class;

    public function definition(): array
    {
        static $year = 2020;

        return [
            'nombre'  => 'Olimpiada Científica ' . $year,
            'gestion' => (string) $year++,
            'estado'  => false,
        ];
    }

    public function activa(): static
    {
        return $this->state(['estado' => true]);
    }
}
