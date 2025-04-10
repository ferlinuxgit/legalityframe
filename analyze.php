<?php
/**
 * analyze.php - Controlador para validar y analizar la URL proporcionada
 */

// Configuración para depuración - solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir una función de gestión de errores personalizada
function errorHandler($errno, $errstr, $errfile, $errline) {
    // Registrar el error en un archivo
    $error_message = date('Y-m-d H:i:s') . " [ERROR] ($errno) $errstr en $errfile línea $errline\n";
    error_log($error_message, 3, 'storage/error_log.txt');
    
    // Si estamos en modo de depuración, mostrar el error
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "<div style='border:1px solid red; padding:10px; margin:10px; background-color:#ffeeee;'>";
        echo "<h3>Error detectado:</h3>";
        echo "<p><strong>Tipo:</strong> $errno</p>";
        echo "<p><strong>Mensaje:</strong> $errstr</p>";
        echo "<p><strong>Archivo:</strong> $errfile (línea $errline)</p>";
        echo "</div>";
    }
    
    // Devolver false para permitir que el gestor de errores estándar de PHP se ejecute también
    return false;
}

// Establecer manejador de errores personalizado
set_error_handler("errorHandler");

// Define una función para gestionar excepciones no capturadas
function exceptionHandler($exception) {
    // Registrar la excepción
    $error_message = date('Y-m-d H:i:s') . " [EXCEPTION] Excepción no capturada: " . $exception->getMessage() . 
                    " en " . $exception->getFile() . " línea " . $exception->getLine() . "\n";
    error_log($error_message, 3, 'storage/error_log.txt');
    
    // Si estamos en modo de depuración, mostrar la excepción
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "<div style='border:1px solid red; padding:10px; margin:10px; background-color:#ffeeee;'>";
        echo "<h3>Excepción no capturada:</h3>";
        echo "<p><strong>Mensaje:</strong> " . $exception->getMessage() . "</p>";
        echo "<p><strong>Archivo:</strong> " . $exception->getFile() . " (línea " . $exception->getLine() . ")</p>";
        echo "<p><strong>Traza:</strong> <pre>" . $exception->getTraceAsString() . "</pre></p>";
        echo "</div>";
    } else {
        // En producción, redirigir al usuario a una página de error genérica
        header('Location: index.php?error=unknown_error');
    }
    
    exit(1);
}

// Establecer manejador de excepciones personalizado
set_exception_handler("exceptionHandler");

// Incluir archivos de configuración y helpers
require_once 'includes/config.php';
require_once 'includes/helpers.php';

/**
 * Obtiene y valida la URL enviada por POST
 * 
 * @return string|false La URL validada o false si no es válida
 */
function getPostedURL() {
    // Verificar si se ha enviado la URL por POST
    if (!isset($_POST['website_url']) || empty($_POST['website_url'])) {
        return false;
    }
    
    $url = trim($_POST['website_url']);
    
    // Validar el formato de la URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    return $url;
}

// Obtener la URL enviada
$url = getPostedURL();

// Verificar si la URL es válida
if ($url === false) {
    // Redirigir al formulario con un mensaje de error
    header('Location: index.php?error=url_invalid');
    exit;
}

// Crear directorio de logs si no existe
if (!is_dir('storage')) {
    mkdir('storage', 0777, true);
}

try {
    // Verificar si podemos acceder a la URL antes de analizarla
    $headers = @get_headers($url);
    if ($headers === false) {
        throw new Exception("No se puede acceder a la URL: $url. Verifica que el sitio esté en línea.");
    }
    
    // Verificar el código de respuesta HTTP
    $http_code = 0;
    foreach ($headers as $header) {
        if (strpos($header, 'HTTP/') !== false) {
            $parts = explode(' ', $header);
            if (isset($parts[1])) {
                $http_code = intval($parts[1]);
            }
        }
    }
    
    if ($http_code >= 400) {
        throw new Exception("La URL devolvió un código de error HTTP: $http_code");
    }
    
    // Realizar el análisis completo de la URL
    $analysis = run_full_analysis($url);
    
    // Guardar el análisis en la sesión para usarlo en result.php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['analysis'] = $analysis;
    $_SESSION['analyzed_url'] = $url;
    
    // Registrar el éxito
    $success_message = date('Y-m-d H:i:s') . " [SUCCESS] Análisis completado para: $url\n";
    error_log($success_message, 3, 'storage/success_log.txt');
    
    // Redirigir a la página de resultados
    header('Location: result.php');
    exit;
    
} catch (Exception $e) {
    // Registrar el error de forma detallada
    $error_details = [
        'url' => $url,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'time' => date('Y-m-d H:i:s')
    ];
    
    // Guardar detalles del error
    $error_json = json_encode($error_details, JSON_PRETTY_PRINT);
    file_put_contents('storage/error_details.json', $error_json, FILE_APPEND);
    
    // Registrar en el log del sistema
    error_log("Error al analizar URL ($url): " . $e->getMessage());
    
    // Intentar registrar con nuestra función personalizada
    if (function_exists('log_event')) {
        log_event("Error al analizar la URL: " . $e->getMessage(), "ERROR");
    }
    
    // Si estamos en modo de depuración, mostrar el error detallado
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "<div style='border:1px solid red; padding:10px; margin:10px; background-color:#ffeeee;'>";
        echo "<h3>Error al analizar la URL:</h3>";
        echo "<p><strong>URL:</strong> $url</p>";
        echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Archivo:</strong> " . $e->getFile() . " (línea " . $e->getLine() . ")</p>";
        echo "<p><strong>Traza:</strong> <pre>" . $e->getTraceAsString() . "</pre></p>";
        echo "<p><a href='index.php'>Volver al inicio</a></p>";
        echo "</div>";
        exit;
    }
    
    // En producción, redirigir al formulario con un mensaje de error genérico
    header('Location: index.php?error=analysis_failed&reason=' . urlencode($e->getMessage()));
    exit;
}

// En caso de cualquier otro error, asegurar que se redirige al usuario
header('Location: index.php?error=unknown_error');
exit;
