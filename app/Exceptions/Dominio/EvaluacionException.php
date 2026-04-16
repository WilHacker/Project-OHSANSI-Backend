<?php

namespace App\Exceptions\Dominio;

use App\Exceptions\AppException;

/**
 * Excepción para errores en la lógica de negocio de Evaluaciones.
 *
 * Ejemplos de uso:
 *   throw new EvaluacionException('Ficha ocupada por otro juez.');        // 409
 *   throw new EvaluacionException('El examen no está en curso.', 422);
 */
class EvaluacionException extends AppException
{
    public function __construct(string $mensaje, int $codigoHttp = 409)
    {
        parent::__construct($mensaje, $codigoHttp);
    }
}
