<?php

namespace App\Http\Requests\Competencia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class AvalarCompetenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('COMPETENCIAS') ?? false;
    }

    public function rules(): array
    {
        return [
            'password_confirmacion' => ['required', 'string'],
        ];
    }

    /**
     * Verifica que la contraseña del usuario autenticado sea correcta
     * antes de permitir la firma digital (aval).
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $usuario = $this->user();

            if (!Hash::check($this->input('password_confirmacion'), $usuario->password)) {
                $v->errors()->add(
                    'password_confirmacion',
                    'La contraseña es incorrecta. No se puede avalar.'
                );
            }
        });
    }
}
