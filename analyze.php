<?php
/**
 * analyze.php - API endpoint para analizar una URL y devolver resultados en JSON
 */

// Configuración para depuración - solo en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// MODO DE PRUEBA SIMPLIFICADO
// Si recibimos test_url por GET, ejecutamos un análisis directo y mostramos los resultados
if (isset($_GET['test_url'])) {
    // Iniciar buffer de salida para capturar errores y mensajes
    ob_start();
    
    // Inicializar variables para almacenar resultados y errores
    $errors = [];
    $result_json = null;
    $url = $_GET['test_url'];
    
    // Validar la URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = "El formato de la URL no es válido.";
    } else {
        try {
            // Incluir archivos necesarios
            require_once 'includes/config.php';
            require_once 'includes/helpers.php';
            
            // Verificar si podemos acceder a la URL
            $headers = @get_headers($url);
            if ($headers === false) {
                $errors[] = "No se puede acceder a la URL. Verifica que el sitio esté en línea.";
            } else {
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
                    $errors[] = "La URL devolvió un código de error HTTP: $http_code";
                } else {
                    // Realizar el análisis
                    if (function_exists('run_full_analysis')) {
                        $analysis = run_full_analysis($url);
                        
                        // Calcular métricas adicionales
                        $legal_documents_missing = 0;
                        if (!$analysis['results']['legal_notice']) $legal_documents_missing++;
                        if (!$analysis['results']['privacy_policy']) $legal_documents_missing++;
                        if (!$analysis['results']['cookies_policy']) $legal_documents_missing++;
                        
                        $compliance_score = (9 - $legal_documents_missing) * 10;
                        
                        // Preparar la respuesta
                        $response = [
                            'success' => true,
                            'url' => $url,
                            'time' => date('Y-m-d H:i:s'),
                            'results' => $analysis['results'],
                            'metrics' => [
                                'legal_documents_missing' => $legal_documents_missing,
                                'compliance_score' => $compliance_score,
                                'platform' => $analysis['results']['ecommerce_platform'] ?: 'No detectada'
                            ]
                        ];
                        
                        $result_json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        $errors[] = "La función run_full_analysis no está definida. Verifica que el archivo includes/helpers.php esté correcto.";
                    }
                }
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
            $error_trace = $e->getTraceAsString();
        }
    }
    
    // Capturar cualquier output adicional (errores PHP, mensajes, etc.)
    $additional_output = ob_get_clean();
    
    // Ahora mostrar el HTML
    header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html>
<head>
    <title>Prueba del Analizador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1 { color: #2563eb; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow: auto; max-height: 600px; }
        .error { color: #e53e3e; padding: 10px; background: #fff5f5; border-radius: 5px; margin-bottom: 10px; }
        .success { color: #38a169; padding: 10px; background: #f0fff4; border-radius: 5px; margin-bottom: 10px; }
        .info { color: #4299e1; padding: 10px; background: #ebf8ff; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Modo de prueba: <?php echo htmlspecialchars($url); ?></h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <strong>Errores encontrados:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($additional_output)): ?>
        <div class="info">
            <strong>Mensajes adicionales:</strong>
            <pre><?php echo htmlspecialchars($additional_output); ?></pre>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_trace)): ?>
        <div class="error">
            <strong>Detalles del error:</strong>
            <pre><?php echo htmlspecialchars($error_trace); ?></pre>
        </div>
    <?php endif; ?>
    
    <?php if ($result_json !== null): ?>
        <div class="success">Análisis completado correctamente</div>
        <h2>Resultado:</h2>
        <pre><?php echo $result_json; ?></pre>
    <?php endif; ?>
    
    <p><a href="index.php">← Volver al inicio</a></p>
</body>
</html>
<?php
    exit; // Terminar el script después de mostrar la página de prueba
}

// MODO API NORMAL - A partir de aquí es el código para el modo API

// Establecer cabeceras para API JSON
header('Content-Type: application/json');

// Incluir archivos de configuración y helpers
require_once 'includes/config.php';
require_once 'includes/helpers.php';

// Definir función para devolver respuestas JSON
function json_response($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Definir función para devolver errores en formato JSON
function json_error($message, $status = 400, $details = null) {
    $response = [
        'success' => false,
        'error' => $message
    ];
    
    if ($details !== null) {
        $response['details'] = $details;
    }
    
    json_response($response, $status);
}

// Crear directorio de logs si no existe
if (!is_dir('storage')) {
    mkdir('storage', 0777, true);
}

// Verificar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Método no permitido. Utiliza POST para enviar la URL.', 405);
}

// Obtener los datos de entrada (por POST o JSON)
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['website_url'])) {
    $url = trim($input['website_url']);
} elseif (isset($_POST['website_url'])) {
    $url = trim($_POST['website_url']);
} else {
    json_error('No se ha proporcionado una URL válida', 400);
}

// Validar el formato de la URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    json_error('El formato de la URL no es válido', 400);
}

try {
    // Verificar si podemos acceder a la URL antes de analizarla
    $headers = @get_headers($url);
    if ($headers === false) {
        json_error('No se puede acceder a la URL. Verifica que el sitio esté en línea.', 400);
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
        json_error("La URL devolvió un código de error HTTP: $http_code", 400);
    }
    
    // Realizar el análisis completo de la URL
    $analysis = run_full_analysis($url);
    
    // Registrar el éxito
    $success_message = date('Y-m-d H:i:s') . " [SUCCESS] Análisis API completado para: $url\n";
    error_log($success_message, 3, 'storage/success_log.txt');
    
    // Preparar la respuesta
    $response = [
        'success' => true,
        'url' => $url,
        'time' => date('Y-m-d H:i:s'),
        'results' => $analysis['results']
    ];
    
    // Calcular métricas adicionales
    $legal_documents_missing = 0;
    if (!$analysis['results']['legal_notice']) $legal_documents_missing++;
    if (!$analysis['results']['privacy_policy']) $legal_documents_missing++;
    if (!$analysis['results']['cookies_policy']) $legal_documents_missing++;
    
    $compliance_score = (9 - $legal_documents_missing) * 10;
    
    $response['metrics'] = [
        'legal_documents_missing' => $legal_documents_missing,
        'compliance_score' => $compliance_score,
        'platform' => $analysis['results']['ecommerce_platform'] ?: 'No detectada'
    ];
    
    // Devolver respuesta JSON con resultados
    json_response($response);
    
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
    error_log("Error API al analizar URL ($url): " . $e->getMessage());
    
    // Intentar registrar con nuestra función personalizada
    if (function_exists('log_event')) {
        log_event("Error API al analizar la URL: " . $e->getMessage(), "ERROR");
    }
    
    // Devolver error como JSON
    $debug_info = defined('DEBUG_MODE') && DEBUG_MODE ? [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString())
    ] : null;
    
    json_error($e->getMessage(), 500, $debug_info);
}
