<?php

/*
|--------------------------------------------------------------------------
| CORS Configuration for PRODUCTION
|--------------------------------------------------------------------------
| Configuración de CORS para ambiente de producción
| Reemplazar el archivo config/cors.php con esta configuración
|--------------------------------------------------------------------------
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Paths que permiten CORS
    |--------------------------------------------------------------------------
    | Define qué rutas pueden ser accedidas desde otros dominios
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
    ],

    /*
    |--------------------------------------------------------------------------
    | Métodos HTTP permitidos
    |--------------------------------------------------------------------------
    */

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    /*
    |--------------------------------------------------------------------------
    | Orígenes permitidos - PRODUCCIÓN
    |--------------------------------------------------------------------------
    | ⚠️ IMPORTANTE: Cambiar '*' por los dominios reales en producción
    | Esto mejora la seguridad significativamente
    */

    'allowed_origins' => [
        // Frontend de aplicación web
        'https://app.dra.gob.pe',

        // Panel administrativo
        'https://admin.dra.gob.pe',

        // Dominio principal (si aplica)
        'https://dra.gob.pe',

        // Subdominios adicionales si es necesario
        // 'https://reportes.dra.gob.pe',

        // Para desarrollo local (remover en producción final)
        // 'http://localhost:5173',
        // 'http://localhost:3000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Patrones de orígenes permitidos
    |--------------------------------------------------------------------------
    | Permite subdominios dinámicos si es necesario
    */

    'allowed_origins_patterns' => [
        // Permite todos los subdominios de dra.gob.pe
        // '/^https:\/\/.*\.dra\.gob\.pe$/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Headers permitidos
    |--------------------------------------------------------------------------
    */

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'X-CSRF-Token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Headers expuestos
    |--------------------------------------------------------------------------
    | Headers que el navegador puede leer en la respuesta
    */

    'exposed_headers' => [
        'Content-Disposition',  // Para descargas de archivos
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    | Tiempo en segundos que el navegador puede cachear la respuesta preflight
    */

    'max_age' => 86400,  // 24 horas

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    | Si se permiten cookies y autenticación
    */

    'supports_credentials' => true,

];
