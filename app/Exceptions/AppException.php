<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Excepción base de la aplicación.
 *
 * Todas las excepciones de dominio extienden esta clase.
 * El handler en bootstrap/app.php las captura y devuelve
 * una respuesta JSON con el código HTTP correspondiente.
 *
 * Uso en un Service:
 *   throw new CompetenciaException('El estado no permite esta operación.');
 *
 * Uso con código HTTP personalizado:
 *   throw new CompetenciaException('Recurso no encontrado.', 404);
 */
class AppException extends RuntimeException
{
    public function __construct(
        string $mensaje,
        private int $codigoHttp = 400,
    ) {
        parent::__construct($mensaje);
    }

    public function getCodigoHttp(): int
    {
        return $this->codigoHttp;
    }
}
