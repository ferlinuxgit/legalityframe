<?php
/**
 * config.php - Configuración global de la aplicación
 */

// Configuración básica
define('APP_NAME', 'Generador de Textos Legales con IA');
define('APP_VERSION', '1.0.0');
define('DEBUG_MODE', true); // Cambiar a false en producción

// Configuración de rutas
define('BASE_PATH', __DIR__ . '/../');
define('STORAGE_PATH', BASE_PATH . 'storage/');
define('TEMPLATES_PATH', BASE_PATH . 'templates/');

// Configuración de OpenAI (Pendiente de completar con claves reales)
define('OPENAI_API_KEY', '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'); // Añadir clave real en producción
define('OPENAI_MODEL', 'gpt-4o'); // Modelo de OpenAI a utilizar

// Configuración de tiempo de espera
define('TIMEOUT_SECONDS', 30);

// Configuración de errores
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de zona horaria
date_default_timezone_set('Europe/Madrid');

// Constantes para el análisis
define('MIN_HTML_SIZE', 1000); // Tamaño mínimo en bytes para considerar válido un HTML
