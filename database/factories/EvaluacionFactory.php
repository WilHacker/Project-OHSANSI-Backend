<?php

namespace Database\Factories;

use App\Models\Competidor;
use App\Models\Evaluacion;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Evaluacion> */
class EvaluacionFactory extends Factory
{
    protected $model = Evaluacion::class;

    public function definition(): array
    {
        return [
            'id_competidor'        => Competidor::factory(),
            'id_examen'            => Examen::factory(),
            'nota'                 => 0.00,
            'estado_participacion' => 'presente',
            'observacion'          => null,
            'resultado_calculado'  => null,
            'bloqueado_por'        => null,
            'fecha_bloqueo'        => null,
            'esta_calificado'      => false,
        ];
    }

    public function calificada(float $nota = 75.0): static
    {
        return $this->state([
            'nota'            => $nota,
            'esta_calificado' => true,
        ]);
    }

    public function ausente(): static
    {
        return $this->state(['estado_participacion' => 'ausente']);
    }

    public function bloqueadaPor(int $userId): static
    {
        return $this->state([
            'bloqueado_por' => $userId,
            'fecha_bloqueo' => now(),
        ]);
    }
}
