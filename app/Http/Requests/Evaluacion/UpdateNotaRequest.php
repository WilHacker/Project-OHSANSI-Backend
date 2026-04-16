<?php

namespace App\Http\Requests\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Evaluacion;
use Illuminate\Validation\Validator;

class UpdateNotaRequest extends FormRequest
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
            'motivo_cambio'        => ['required', 'string', 'min:5', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($v) {
            $id = $this->route('id') ?? $this->route('evaluacion');

            if (!$id) {
                return;
            }

            $evaluacion = Evaluacion::with('examen')->find($id);

            if (!$evaluacion) {
                $v->errors()->add('id', 'La evaluación no existe.');
                return;
            }

            if ($this->input('estado_participacion') === 'presente') {
                $nota   = (float) $this->input('nota');
                $maxima = (float) $evaluacion->examen->maxima_nota;

                if ($nota > $maxima) {
                    $v->errors()->add('nota', "La nota corregida ({$nota}) supera el máximo permitido ({$maxima}).");
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'nota.required'            => 'La nueva nota es obligatoria.',
            'motivo_cambio.required'   => 'Por auditoría, debe explicar el motivo del cambio de nota.',
            'motivo_cambio.min'        => 'El motivo debe ser explicativo (mínimo 5 caracteres).',
        ];
    }
}
