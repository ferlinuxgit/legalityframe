<?php
/**
 * analyze.php - Controlador para validar y analizar la URL proporcionada
 */

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

try {
    // Realizar el análisis completo de la URL
    $analysis = run_full_analysis($url);
    
    // Guardar el análisis en la sesión para usarlo en result.php
    session_start();
    $_SESSION['analysis'] = $analysis;
    $_SESSION['analyzed_url'] = $url;
    
    // Redirigir a la página de resultados
    header('Location: result.php');
    exit;
    
} catch (Exception $e) {
    // Registrar el error
    log_event("Error al analizar la URL: " . $e->getMessage(), "ERROR");
    
    // Redirigir al formulario con un mensaje de error
    header('Location: index.php?error=analysis_failed');
    exit;
}
