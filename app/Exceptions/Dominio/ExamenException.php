<?php

namespace App\Exceptions\Dominio;

use App\Exceptions\AppException;

/**
 * Excepción para errores en la lógica de negocio de Exámenes.
 */
class ExamenException extends AppException
{
    public function __construct(string $mensaje, int $codigoHttp = 422)
    {
        parent::__construct($mensaje, $codigoHttp);
    }
}
