<?php

namespace App\Http\Requests\Olimpiada;

use Illuminate\Foundation\Http\FormRequest;

class StoreOlimpiadaAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'  => ['required', 'string', 'max:255', 'unique:olimpiada,nombre'],
            'gestion' => ['required', 'string', 'size:4', 'unique:olimpiada,gestion'],
            'estado'  => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique'  => 'Ya existe una olimpiada con este nombre.',
            'gestion.unique' => 'Ya existe una olimpiada con esta gestión.',
        ];
    }
}
