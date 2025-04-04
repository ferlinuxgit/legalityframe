<?php

/**
 * LegalityFrame - Automatización de Cumplimiento Legal
 * 
 * Punto de entrada de la aplicación
 */

// Definir la ruta base de la aplicación
define('BASE_PATH', dirname(__DIR__));

// Cargar el autoloader de Composer
require BASE_PATH . '/vendor/autoload.php';

// Cargar funciones auxiliares
require BASE_PATH . '/app/Helpers/functions.php';

// Cargar constantes globales
require BASE_PATH . '/config/constants.php';

// Cargar configuración
$config = require BASE_PATH . '/config/app.php';
$GLOBALS['config'] = $config;

// Iniciar o reanudar sesión
session_start();

// Crear la instancia de la aplicación
$app = new App\Config\App($config);

// Ejecutar la aplicación
$app->run(); 