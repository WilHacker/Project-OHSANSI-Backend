<?php

namespace App\Http\Requests\Catalogo;

use Illuminate\Foundation\Http\FormRequest;

class StoreNivelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:nivel,nombre'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique' => 'El nombre del nivel ya se encuentra registrado.',
        ];
    }
}
