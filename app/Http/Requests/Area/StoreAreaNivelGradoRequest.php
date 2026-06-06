<?php

namespace App\Http\Requests\Area;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaNivelGradoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*'                       => ['array'],
            '*.id_area'               => ['required', 'integer', 'exists:area,id_area'],
            '*.id_nivel'              => ['required', 'integer', 'exists:nivel,id_nivel'],
            '*.id_grado_escolaridad'  => ['required', 'integer', 'exists:grado_escolaridad,id_grado_escolaridad'],
            '*.activo'                => ['required', 'boolean'],
        ];
    }
}
