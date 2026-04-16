<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rutas donde CORS aplica
    |--------------------------------------------------------------------------
    | Incluimos api/* y el endpoint de autenticación de WebSockets.
    */
    'paths' => ['api/*', 'broadcasting/auth'],

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Orígenes permitidos
    |--------------------------------------------------------------------------
    | En desarrollo se usan los puertos locales de Vite/React.
    | En producción configurar CORS_ALLOWED_ORIGINS en el .env con el
    | dominio real del frontend (sin comodines).
    */
    'allowed_origins' => array_filter(
        explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://localhost:5173'))
    ),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Credenciales
    |--------------------------------------------------------------------------
    | Necesario para que Sanctum envíe/reciba cookies de sesión
    | y para que los WebSockets se autentiquen correctamente.
    */
    'supports_credentials' => true,

];
