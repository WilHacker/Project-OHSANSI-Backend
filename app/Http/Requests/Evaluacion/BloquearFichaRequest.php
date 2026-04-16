<?php

namespace App\Http\Requests\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;

class BloquearFichaRequest extends FormRequest
{
    /**
     * La autorización real se valida en el Service (si el juez está asignado al área/nivel).
     * El middleware auth:sanctum ya garantiza que el usuario existe y está autenticado.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [];
    }
}
