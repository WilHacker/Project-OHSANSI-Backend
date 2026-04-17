<?php

namespace App\Http\Requests\Examen;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidarReglasExamen;

class UpdateExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('EXAMENES') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string', 'max:255'],
            'ponderacion' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'maxima_nota' => ['sometimes', 'numeric', 'min:1'],
            'fecha_hora_inicio' => ['nullable', 'date'],
            'tipo_regla' => ['nullable', 'in:nota_corte'],
            'configuracion_reglas' => [
                'nullable',
                'array',
                new ValidarReglasExamen($this->input('tipo_regla') ?? (($examen = $this->route('examen')) instanceof \App\Models\Examen ? $examen->tipo_regla : null))
            ],
        ];
    }
}
