<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Textos Legales con IA</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Generador de Textos Legales con IA</h1>
            <p class="subtitle">Analiza tu sitio web y genera documentos legales personalizados</p>
        </header>

        <main>
            <?php
            // Mostrar mensajes de error si existen
            if (isset($_GET['error'])) {
                $error_type = $_GET['error'];
                $error_message = '';
                
                switch ($error_type) {
                    case 'url_invalid':
                        $error_message = 'La URL introducida no es válida. Por favor, asegúrate de que comienza con http:// o https://';
                        break;
                    case 'analysis_failed':
                        $error_message = 'No se pudo analizar la URL proporcionada. Por favor, inténtalo de nuevo o prueba con otra URL.';
                        break;
                    case 'no_analysis':
                        $error_message = 'No hay datos de análisis disponibles. Por favor, introduce una URL para analizar.';
                        break;
                    case 'unknown_error':
                        $error_message = 'Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo.';
                        break;
                }
                
                if (!empty($error_message)) {
                    echo '<div class="error-banner">' . htmlspecialchars($error_message) . '</div>';
                }
            }
            ?>
            
            <section class="form-container">
                <h2>Ingresa la URL de tu sitio web</h2>
                <p class="helper-text">Analizaremos tu sitio web para detectar qué documentos legales necesitas.</p>

                <form id="url-form" action="analyze.php" method="POST" onsubmit="return validateURLBeforeSubmit()">
                    <div class="form-group">
                        <label for="website-url">URL del sitio web:</label>
                        <input 
                            type="url" 
                            id="website-url" 
                            name="website_url" 
                            placeholder="https://ejemplo.com" 
                            pattern="https?://.+" 
                            title="La URL debe comenzar con http:// o https://" 
                            required
                        >
                        <span class="input-hint">Incluye http:// o https:// al inicio</span>
                    </div>

                    <div class="form-error" id="url-error"></div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Analizar sitio web</button>
                    </div>
                </form>
            </section>

            <section class="info-container">
                <h3>¿Cómo funciona?</h3>
                <ol>
                    <li>Ingresa la URL de tu sitio web</li>
                    <li>Nuestro sistema analizará automáticamente tu sitio</li>
                    <li>Te mostraremos qué documentos legales necesitas</li>
                    <li>Generamos textos personalizados con IA</li>
                    <li>Descarga tus documentos en formato HTML, PDF y Word</li>
                </ol>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Generador Legal IA. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
