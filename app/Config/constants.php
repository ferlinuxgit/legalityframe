<?php

/**
 * Archivo de constantes globales para la aplicación
 */

// Estados de usuario
define('USER_STATUS_PENDING', 0);   // Cuenta creada pero pendiente de verificación
define('USER_STATUS_ACTIVE', 1);    // Cuenta activa y verificada
define('USER_STATUS_SUSPENDED', 2); // Cuenta suspendida temporalmente
define('USER_STATUS_BANNED', 3);    // Cuenta baneada permanentemente

// Roles de usuario
define('USER_ROLE_USER', 0);        // Usuario normal
define('USER_ROLE_ADMIN', 1);       // Administrador

// Idiomas disponibles
define('AVAILABLE_LANGUAGES', [
    'es' => 'Español',
    'en' => 'English'
]);

// Configuración de rutas
define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('RESOURCES_PATH', BASE_PATH . '/resources');

// Configuración de URLs
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . 
    (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost')); 