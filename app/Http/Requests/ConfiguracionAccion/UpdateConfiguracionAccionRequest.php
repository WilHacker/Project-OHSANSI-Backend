<?php

namespace App\Http\Requests\ConfiguracionAccion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfiguracionAccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('ACTIVIDADES_FASES') ?? false;
    }

    public function rules(): array
    {
        return [
            'configuraciones'                                    => ['required', 'array', 'min:1'],
            'configuraciones.*.id_configuracion_accion'          => ['required', 'integer', 'exists:configuracion_accion,id_configuracion_accion'],
            'configuraciones.*.habilitada'                       => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'configuraciones.required'                                        => 'No se enviaron datos de configuración.',
            'configuraciones.array'                                           => 'El formato de configuración debe ser un array.',
            'configuraciones.*.id_configuracion_accion.exists'                => 'Uno de los registros de configuración no existe.',
        ];
    }
}
