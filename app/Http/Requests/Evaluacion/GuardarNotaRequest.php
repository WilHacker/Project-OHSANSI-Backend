<?php

namespace App\Http\Requests\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Evaluacion;
use Illuminate\Validation\Validator;

class GuardarNotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nota'                 => ['required', 'numeric', 'min:0'],
            'estado_participacion' => ['required', 'in:presente,ausente,descalificado_etica'],
            'observacion'          => ['nullable', 'string', 'max:255'],
            'motivo_cambio'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $idEvaluacion = $this->route('id');

            if (!$idEvaluacion) {
                return;
            }

            $evaluacion = Evaluacion::with('examen')->find($idEvaluacion);

            if (!$evaluacion) {
                $v->errors()->add('id', 'La evaluación solicitada no existe.');
                return;
            }

            if ($this->input('estado_participacion') === 'presente') {
                $nota    = (float) $this->input('nota');
                $maxima  = (float) $evaluacion->examen->maxima_nota;

                if ($nota > $maxima) {
                    $v->errors()->add(
                        'nota',
                        "La nota ingresada ({$nota}) excede el máximo permitido ({$maxima}) para este examen."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'nota.required'                    => 'El campo nota es obligatorio.',
            'nota.numeric'                     => 'La nota debe ser un valor numérico.',
            'nota.min'                         => 'La nota no puede ser negativa.',
            'estado_participacion.required'    => 'Debe seleccionar el estado de participación.',
            'estado_participacion.in'          => 'El estado seleccionado no es válido.',
        ];
    }
}
