<?php

/**
 * Configuración principal de la aplicación
 */

return [
    'app' => [
        'name' => 'LegalityFrame',
        'version' => '1.0.0',
        'url' => 'http://localhost', // Cambiar en producción
        'timezone' => 'Europe/Madrid',
        'locale' => 'es',
        'debug' => true, // Cambiar a false en producción
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'legalityframe',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],
    
    'mail' => [
        'host' => 'smtp.mailtrap.io',
        'port' => 2525,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from' => [
            'address' => 'info@legalityframe.com',
            'name' => 'LegalityFrame',
        ],
    ],
    
    'log' => [
        'path' => BASE_PATH . '/storage/logs',
        'level' => 'debug', // debug, info, notice, warning, error, critical, alert, emergency
    ],
    
    'openai' => [
        'api_key' => '', // Definir en variables de entorno
        'model' => 'gpt-4',
        'temperature' => 0.2,
    ],
    
    'session' => [
        'lifetime' => 120, // En minutos
        'secure' => false, // Activar en producción con HTTPS
        'http_only' => true,
        'same_site' => 'lax', // none, lax, strict
    ],
    
    'stripe' => [
        'key' => '', // Clave pública
        'secret' => '', // Clave secreta
        'webhook_secret' => '', // Clave de webhook
    ],
]; 