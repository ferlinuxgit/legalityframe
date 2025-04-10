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
    $status_class = $result ? 'status-ok' : 'status-error';
    $text = $result ? $positive_text : $negative_text;
    
    return '
    <div class="analysis-item">
        <div class="analysis-icon ' . $status_class . '">' . $icon . '</div>
        <div class="analysis-content">
            <h4>' . $label . '</h4>
            <p>' . $text . '</p>
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Análisis - <?php echo htmlspecialchars($url); ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Análisis Legal Completado</h1>
            <p class="subtitle">Resultados para: <strong><?php echo htmlspecialchars($url); ?></strong></p>
        </header>

        <main>
            <section class="analysis-container">
                <div class="analysis-header">
                    <h2>Diagnóstico Legal de tu Sitio Web</h2>
                    <p class="analysis-date">Fecha de análisis: <?php echo $analysis['time']; ?></p>
                </div>

                <div class="analysis-results">
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

                <div class="analysis-summary">
                    <h3>Resumen del análisis</h3>
                    <ul>
                        <li>Plataforma detectada: <strong><?php echo htmlspecialchars($platform); ?></strong></li>
                        <li>Documentos legales faltantes: <strong><?php echo $legal_documents_missing; ?></strong></li>
                        <li>Puntuación de cumplimiento: <strong><?php echo (9 - $legal_documents_missing) * 10; ?>%</strong></li>
                    </ul>
                </div>

                <div class="analysis-cta">
                    <form action="generate.php" method="POST">
                        <input type="hidden" name="generate_from_analysis" value="1">
                        <button type="submit" class="btn btn-primary">Generar textos legales personalizados</button>
                    </form>
                    <a href="index.php" class="btn btn-secondary">Analizar otra web</a>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Generador Legal IA. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
