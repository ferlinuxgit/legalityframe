<?php
/**
 * result.php - Muestra los resultados del análisis de la URL
 */

// Incluir archivos de configuración
require_once 'includes/config.php';
require_once 'includes/helpers.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si existe el análisis en la sesión
if (!isset($_SESSION['analysis']) || !isset($_SESSION['analyzed_url'])) {
    // Redirigir al formulario si no hay datos
    header('Location: index.php?error=no_analysis');
    exit;
}

// Obtener los datos del análisis
$analysis = $_SESSION['analysis'];
$url = $_SESSION['analyzed_url'];

/**
 * Función para renderizar un resultado con iconos
 * 
 * @param bool $result Resultado de la verificación
 * @param string $label Etiqueta del resultado
 * @param string $positive_text Texto para resultado positivo
 * @param string $negative_text Texto para resultado negativo
 * @return string HTML del resultado formateado
 */
function render_analysis_item($result, $label, $positive_text, $negative_text) {
    $icon = $result ? '✅' : '❌';
    $bg_color = $result ? 'bg-green-50' : 'bg-red-50';
    $border_color = $result ? 'border-green-200' : 'border-red-200';
    $text = $result ? $positive_text : $negative_text;
    
    return '
    <div class="flex p-4 mb-4 rounded-lg border ' . $bg_color . ' ' . $border_color . '">
        <div class="text-2xl mr-4">' . $icon . '</div>
        <div class="flex-1">
            <h4 class="font-semibold text-gray-800 mb-1">' . $label . '</h4>
            <p class="text-gray-600">' . $text . '</p>
        </div>
    </div>';
}

// Calcular cuántos documentos legales faltan
$legal_documents_missing = 0;
if (!$analysis['results']['legal_notice']) $legal_documents_missing++;
if (!$analysis['results']['privacy_policy']) $legal_documents_missing++;
if (!$analysis['results']['cookies_policy']) $legal_documents_missing++;

// Determinar la plataforma detectada
$platform = $analysis['results']['ecommerce_platform'] ? $analysis['results']['ecommerce_platform'] : 'No detectada';

// Calcular porcentaje de cumplimiento
$compliance_score = (9 - $legal_documents_missing) * 10;
$compliance_color = $compliance_score >= 80 ? 'text-green-600' : ($compliance_score >= 50 ? 'text-yellow-600' : 'text-red-600');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Análisis - <?php echo htmlspecialchars($url); ?></title>
    <!-- Tailwind CSS via CDN (versión específica) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Análisis Legal Completado</h1>
            <p class="text-xl text-gray-600">Resultados para: <strong><?php echo htmlspecialchars($url); ?></strong></p>
        </header>

        <main>
            <section class="bg-white rounded-lg shadow-md p-8 mb-8">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Diagnóstico Legal de tu Sitio Web</h2>
                    <p class="text-gray-500 text-sm mt-2">Fecha de análisis: <?php echo $analysis['time']; ?></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- HTTPS -->
                    <?php echo render_analysis_item(
                        $analysis['results']['ssl'],
                        'Conexión Segura (HTTPS)',
                        'Tu web usa HTTPS, lo que proporciona una conexión segura a tus usuarios.',
                        'Tu web no usa HTTPS. Recomendamos implementar un certificado SSL para mejorar la seguridad y SEO.'
                    ); ?>

                    <!-- Aviso Legal -->
                    <?php echo render_analysis_item(
                        $analysis['results']['legal_notice'],
                        'Aviso Legal',
                        'Detectamos un enlace a tu Aviso Legal.',
                        'No encontramos un Aviso Legal en tu web. Es recomendable incluirlo para cumplir con la legislación.'
                    ); ?>

                    <!-- Política de Privacidad -->
                    <?php echo render_analysis_item(
                        $analysis['results']['privacy_policy'],
                        'Política de Privacidad',
                        'Detectamos una Política de Privacidad en tu web.',
                        'No encontramos una Política de Privacidad. Es obligatoria si recoges datos personales.'
                    ); ?>

                    <!-- Política de Cookies -->
                    <?php echo render_analysis_item(
                        $analysis['results']['cookies_policy'],
                        'Política de Cookies',
                        'Detectamos información sobre Cookies en tu web.',
                        'No encontramos una Política de Cookies. Es obligatoria si utilizas cookies no esenciales.'
                    ); ?>

                    <!-- Formularios -->
                    <?php echo render_analysis_item(
                        $analysis['results']['has_forms'],
                        'Formularios de contacto o suscripción',
                        'Detectamos formularios en tu web. Asegúrate de mencionar el tratamiento de datos en tu Política de Privacidad.',
                        'No detectamos formularios en tu web.'
                    ); ?>

                    <!-- Google Analytics -->
                    <?php echo render_analysis_item(
                        $analysis['results']['uses_google_analytics'],
                        'Google Analytics',
                        'Detectamos que usas Google Analytics. Debes informar sobre esto en tu Política de Cookies.',
                        'No detectamos Google Analytics en tu web.'
                    ); ?>

                    <!-- Facebook Pixel -->
                    <?php echo render_analysis_item(
                        $analysis['results']['uses_facebook_pixel'],
                        'Facebook Pixel',
                        'Detectamos Facebook Pixel en tu web. Debes informar sobre esto en tu Política de Cookies.',
                        'No detectamos Facebook Pixel en tu web.'
                    ); ?>

                    <!-- Botones de pago -->
                    <?php echo render_analysis_item(
                        $analysis['results']['has_checkout'],
                        'Funcionalidad de compra',
                        'Detectamos elementos de checkout o compra. Recuerda incluir los Términos y Condiciones de venta.',
                        'No detectamos elementos de checkout o compra en tu web.'
                    ); ?>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Resumen del análisis</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
                            Plataforma detectada: <strong class="ml-2"><?php echo htmlspecialchars($platform); ?></strong>
                        </li>
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-yellow-500 rounded-full mr-2"></span>
                            Documentos legales faltantes: <strong class="ml-2"><?php echo $legal_documents_missing; ?></strong>
                        </li>
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                            Puntuación de cumplimiento: <strong class="ml-2 <?php echo $compliance_color; ?>"><?php echo $compliance_score; ?>%</strong>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <form action="generate.php" method="POST">
                        <input type="hidden" name="generate_from_analysis" value="1">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition transform hover:-translate-y-0.5 w-full sm:w-auto">
                            Generar textos legales personalizados
                        </button>
                    </form>
                    <a href="index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition text-center w-full sm:w-auto">
                        Analizar otra web
                    </a>
                </div>
            </section>
        </main>

        <footer class="mt-12 text-center text-gray-600 text-sm">
            <p>&copy; <?php echo date('Y'); ?> Generador Legal IA. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
