<?php

namespace Database\Factories;

use App\Models\AreaNivel;
use App\Models\Competencia;
use App\Models\FaseGlobal;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Competencia> */
class CompetenciaFactory extends Factory
{
    protected $model = Competencia::class;

    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('-10 days', 'now');
        $fin    = fake()->dateTimeBetween('now', '+30 days');

        return [
            'id_fase_global'          => FaseGlobal::factory(),
            'id_area_nivel'           => AreaNivel::factory(),
            'fecha_inicio'            => $inicio,
            'fecha_fin'               => $fin,
            'estado_fase'             => 'borrador',
            'criterio_clasificacion'  => 'suma_ponderada',
            'id_usuario_aval'         => null,
            'fecha_aval'              => null,
        ];
    }

    public function publicada(): static
    {
        return $this->state(['estado_fase' => 'publicada']);
    }

    public function enProceso(): static
    {
        return $this->state(['estado_fase' => 'en_proceso']);
    }

    public function cerrada(): static
    {
        return $this->state(['estado_fase' => 'cerrada']);
    }
}
