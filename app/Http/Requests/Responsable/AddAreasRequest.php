<?php

namespace App\Http\Requests\Responsable;

use Illuminate\Foundation\Http\FormRequest;

class AddAreasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('RESPONSABLES') ?? false;
    }

    public function rules(): array
    {
        return [
            'id_olimpiada' => ['required', 'integer', 'exists:olimpiada,id_olimpiada'],
            'areas'        => ['required', 'array', 'min:1'],
            'areas.*'      => ['integer', 'exists:area,id_area'],
        ];
    }
}
