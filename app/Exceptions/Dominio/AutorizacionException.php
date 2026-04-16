<?php

namespace App\Exceptions\Dominio;

use App\Exceptions\AppException;

/**
 * Excepción para acciones sin autorización de dominio.
 *
 * Usar cuando la regla de negocio prohíbe la acción
 * (no confundir con Spatie/middleware que devuelve 403 automáticamente).
 *
 * Ejemplo:
 *   throw new AutorizacionException('Solo el responsable del área puede avalar.');
 */
class AutorizacionException extends AppException
{
    public function __construct(string $mensaje, int $codigoHttp = 403)
    {
        parent::__construct($mensaje, $codigoHttp);
    }
}
