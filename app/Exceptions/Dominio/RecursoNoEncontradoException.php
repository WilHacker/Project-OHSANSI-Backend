<?php

namespace App\Exceptions\Dominio;

use App\Exceptions\AppException;

/**
 * Excepción para recursos no encontrados en lógica de dominio.
 *
 * Alternativa semántica a lanzar un 404 desde el Service
 * sin depender de clases HTTP.
 *
 * Ejemplo:
 *   throw new RecursoNoEncontradoException('Competencia no encontrada.');
 */
class RecursoNoEncontradoException extends AppException
{
    public function __construct(string $mensaje, int $codigoHttp = 404)
    {
        parent::__construct($mensaje, $codigoHttp);
    }
}
