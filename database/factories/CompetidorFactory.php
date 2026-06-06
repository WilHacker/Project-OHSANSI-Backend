<?php

namespace Database\Factories;

use App\Models\AreaNivel;
use App\Models\Competidor;
use App\Models\Departamento;
use App\Models\GradoEscolaridad;
use App\Models\Institucion;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Competidor> */
class CompetidorFactory extends Factory
{
    protected $model = Competidor::class;

    public function definition(): array
    {
        return [
            'id_persona'           => Persona::factory(),
            'id_institucion'       => Institucion::factory(),
            'id_departamento'      => Departamento::factory(),
            'id_area_nivel'        => AreaNivel::factory(),
            'id_grado_escolaridad' => GradoEscolaridad::factory(),
            'id_archivo_csv'       => null,
            'contacto_tutor'       => fake()->optional()->numerify('7########'),
            'tutor_academico'      => fake()->optional()->name(),
            'genero'               => fake()->randomElement(['M', 'F']),
            'estado_evaluacion'    => 'disponible',
        ];
    }
}
