<?php

namespace Database\Factories;

use App\Models\CronogramaFase;
use App\Models\FaseGlobal;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CronogramaFase> */
class CronogramaFaseFactory extends Factory
{
    protected $model = CronogramaFase::class;

    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('-1 month', 'now');
        $fin    = fake()->dateTimeBetween('now', '+1 month');

        return [
            'id_fase_global' => FaseGlobal::factory(),
            'fecha_inicio'   => $inicio,
            'fecha_fin'      => $fin,
            'estado'         => 0,
        ];
    }

    public function activo(): static
    {
        return $this->state(['estado' => 1]);
    }
}
