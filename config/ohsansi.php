<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sala de Evaluación — Bloqueo de fichas
    |--------------------------------------------------------------------------
    | Tiempo máximo (en minutos) que un juez puede tener bloqueada una ficha
    | sin guardar nota antes de que sea liberada como "zombie".
    */
    'bloqueo_timeout_minutos' => (int) env('OHSANSI_BLOQUEO_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Nota mínima de aprobación por defecto
    |--------------------------------------------------------------------------
    | Se usa cuando la competencia no tiene parámetros configurados ni
    | reglas en el examen.
    */
    'nota_minima_default' => (float) env('OHSANSI_NOTA_MINIMA', 51.0),

    /*
    |--------------------------------------------------------------------------
    | Importación CSV
    |--------------------------------------------------------------------------
    | Configuración para el procesamiento de archivos de importación.
    | En producción, mover la importación a un Job de cola.
    */
    'importacion' => [
        'max_competidores_por_request' => (int) env('OHSANSI_IMPORTACION_MAX', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate limiting personalizado
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'login' => (int) env('RATE_LIMIT_LOGIN', 10),
        'api'   => (int) env('RATE_LIMIT_API', 120),
    ],

];
