<?php

namespace Database\Factories;

use App\Models\Competencia;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Examen> */
class ExamenFactory extends Factory
{
    protected $model = Examen::class;

    public function definition(): array
    {
        return [
            'id_competencia'      => Competencia::factory(),
            'nombre'              => 'Examen ' . fake()->randomElement(['Teórico', 'Práctico', 'Oral', 'Escrito']),
            'ponderacion'         => '100.00',
            'maxima_nota'         => '100.00',
            'fecha_hora_inicio'   => fake()->dateTimeBetween('now', '+7 days'),
            'tipo_regla'          => 'nota_corte',
            'configuracion_reglas' => ['nota_minima' => 51],
            'estado_ejecucion'    => 'no_iniciada',
            'fecha_inicio_real'   => null,
        ];
    }

    public function enCurso(): static
    {
        return $this->state([
            'estado_ejecucion'  => 'en_curso',
            'fecha_inicio_real' => now(),
        ]);
    }

    public function finalizado(): static
    {
        return $this->state([
            'estado_ejecucion'  => 'finalizada',
            'fecha_inicio_real' => now()->subHour(),
        ]);
    }
}
