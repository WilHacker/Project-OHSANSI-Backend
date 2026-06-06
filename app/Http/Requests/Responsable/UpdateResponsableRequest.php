<?php

namespace App\Http\Requests\Responsable;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponsableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('RESPONSABLES') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre'   => ['sometimes', 'string', 'max:50'],
            'apellido' => ['sometimes', 'string', 'max:50'],
        ];
    }
}
