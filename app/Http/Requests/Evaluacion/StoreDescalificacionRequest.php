<?php

namespace App\Http\Requests\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;

class StoreDescalificacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_competidor' => ['required', 'integer', 'exists:competidor,id_competidor'],
            'observaciones' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_competidor.exists' => 'El competidor no existe.',
        ];
    }
}
