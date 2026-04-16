<?php

namespace App\Exceptions\Dominio;

use App\Exceptions\AppException;

/**
 * Excepción para errores en la lógica de negocio de Competencias.
 *
 * Ejemplos de uso:
 *   throw new CompetenciaException('La competencia ya fue publicada.');
 *   throw new CompetenciaException('No encontrada.', 404);
 */
class CompetenciaException extends AppException
{
    public function __construct(string $mensaje, int $codigoHttp = 422)
    {
        parent::__construct($mensaje, $codigoHttp);
    }
}
