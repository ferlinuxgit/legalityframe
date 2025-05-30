<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Textos Legales con IA</title>
    <!-- Tailwind CSS via CDN (versión específica) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Generador de Textos Legales con IA</h1>
            <p class="text-xl text-gray-600">Analiza tu sitio web y genera documentos legales personalizados</p>
        </header>

        <main>
            <!-- Contenedor para mensajes de error y alertas -->
            <div id="alert-container" class="mb-6"></div>
            
            <!-- Formulario inicial -->
            <section id="form-section" class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ingresa la URL de tu sitio web</h2>
                <p class="text-gray-600 mb-6">Analizaremos tu sitio web para detectar qué documentos legales necesitas.</p>

                <form id="url-form" class="mb-4">
                    <div class="mb-6">
                        <label for="website-url" class="block text-gray-700 font-medium mb-2">URL del sitio web:</label>
                        <input 
                            type="url" 
                            id="website-url" 
                            name="website_url" 
                            placeholder="https://ejemplo.com" 
                            pattern="https?://.+" 
                            title="La URL debe comenzar con http:// o https://" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                        <span class="text-sm text-gray-500 mt-2 block">Incluye http:// o https:// al inicio</span>
                    </div>

                    <div id="url-error" class="text-red-600 text-sm min-h-[1.5rem] mb-4"></div>

                    <div class="text-center">
                        <button type="submit" id="submit-button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition transform hover:-translate-y-0.5">
                            Analizar sitio web
                        </button>
                    </div>
                </form>
            </section>

            <!-- Sección de resultados (oculta inicialmente) -->
            <section id="results-section" class="bg-white rounded-lg shadow-md p-8 mb-8 hidden">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Diagnóstico Legal de tu Sitio Web</h2>
                    <p class="text-gray-500 text-sm mt-2">URL analizada: <span id="analyzed-url" class="font-medium"></span></p>
                    <p class="text-gray-500 text-sm mt-1">Fecha de análisis: <span id="analysis-time" class="font-medium"></span></p>
                </div>

                <div id="results-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Aquí se insertarán los resultados dinámicamente -->
                </div>

                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Resumen del análisis</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
                            Plataforma detectada: <strong id="platform" class="ml-2"></strong>
                        </li>
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-yellow-500 rounded-full mr-2"></span>
                            Documentos legales faltantes: <strong id="missing-docs" class="ml-2"></strong>
                        </li>
                        <li class="flex items-center">
                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                            Puntuación de cumplimiento: <strong id="compliance-score" class="ml-2"></strong>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button id="generate-button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition transform hover:-translate-y-0.5 w-full sm:w-auto">
                        Generar textos legales personalizados
                    </button>
                    <button id="new-analysis-button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition text-center w-full sm:w-auto">
                        Analizar otra web
                    </button>
                </div>
            </section>

            <!-- Indicador de carga -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto mb-4"></div>
                    <p class="text-gray-700">Analizando sitio web...</p>
                </div>
            </div>

            <section class="bg-white rounded-lg shadow-md p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">¿Cómo funciona?</h3>
                <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                    <li>Ingresa la URL de tu sitio web</li>
                    <li>Nuestro sistema analizará automáticamente tu sitio</li>
                    <li>Te mostraremos qué documentos legales necesitas</li>
                    <li>Generamos textos personalizados con IA</li>
                    <li>Descarga tus documentos en formato HTML, PDF y Word</li>
                </ol>
            </section>
        </main>

        <footer class="mt-12 text-center text-gray-600 text-sm">
            <p>&copy; 2025 Generador Legal IA. Todos los derechos reservados.</p>
        </footer>
    </div>

    <!-- Script para controlar la aplicación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos del DOM
            const urlForm = document.getElementById('url-form');
            const urlInput = document.getElementById('website-url');
            const urlError = document.getElementById('url-error');
            const submitButton = document.getElementById('submit-button');
            const formSection = document.getElementById('form-section');
            const resultsSection = document.getElementById('results-section');
            const resultsContainer = document.getElementById('results-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const alertContainer = document.getElementById('alert-container');
            const analyzedUrlSpan = document.getElementById('analyzed-url');
            const analysisTimeSpan = document.getElementById('analysis-time');
            const platformSpan = document.getElementById('platform');
            const missingDocsSpan = document.getElementById('missing-docs');
            const complianceScoreSpan = document.getElementById('compliance-score');
            const generateButton = document.getElementById('generate-button');
            const newAnalysisButton = document.getElementById('new-analysis-button');
            
            // Validación del formulario
            urlForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const url = urlInput.value.trim();
                
                // Expresión regular para validar URL
                const urlRegex = /^https?:\/\/.+$/;
                
                if (!url) {
                    showError(urlError, 'Por favor, introduce la URL de tu sitio web.');
                    return;
                } else if (!urlRegex.test(url)) {
                    showError(urlError, 'La URL debe comenzar con http:// o https://');
                    return;
                }
                
                // Limpiar error y comenzar análisis
                urlError.textContent = '';
                analyzeWebsite(url);
            });
            
            // Función para analizar el sitio web
            function analyzeWebsite(url) {
                // Mostrar indicador de carga
                loadingIndicator.classList.remove('hidden');
                
                // Limpiar alertas previas
                alertContainer.innerHTML = '';
                
                // Datos para enviar
                const data = {
                    website_url: url
                };
                
                // Llamada a la API
                fetch('analyze.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    // Ocultar indicador de carga
                    loadingIndicator.classList.add('hidden');
                    
                    if (data.success) {
                        // Mostrar resultados
                        displayResults(data);
                    } else {
                        // Mostrar error
                        showAlert('error', data.error || 'Ha ocurrido un error durante el análisis.');
                    }
                })
                .catch(error => {
                    // Ocultar indicador de carga
                    loadingIndicator.classList.add('hidden');
                    
                    // Mostrar error
                    showAlert('error', 'Error de conexión: No se pudo contactar con el servidor.');
                    console.error('Error:', error);
                });
            }
            
            // Función para mostrar los resultados
            function displayResults(data) {
                // Actualizar información de la URL analizada
                analyzedUrlSpan.textContent = data.url;
                analysisTimeSpan.textContent = data.time;
                
                // Actualizar métricas
                platformSpan.textContent = data.metrics.platform;
                missingDocsSpan.textContent = data.metrics.legal_documents_missing;
                
                // Actualizar score con color apropiado
                const score = data.metrics.compliance_score;
                complianceScoreSpan.textContent = score + '%';
                complianceScoreSpan.className = score >= 80 ? 'ml-2 text-green-600' : 
                                              (score >= 50 ? 'ml-2 text-yellow-600' : 'ml-2 text-red-600');
                
                // Limpiar contenedor de resultados
                resultsContainer.innerHTML = '';
                
                // Definir los resultados a mostrar
                const items = [
                    {
                        key: 'ssl',
                        label: 'Conexión Segura (HTTPS)',
                        positive: 'Tu web usa HTTPS, lo que proporciona una conexión segura a tus usuarios.',
                        negative: 'Tu web no usa HTTPS. Recomendamos implementar un certificado SSL para mejorar la seguridad y SEO.'
                    },
                    {
                        key: 'legal_notice',
                        label: 'Aviso Legal',
                        positive: 'Detectamos un enlace a tu Aviso Legal.',
                        negative: 'No encontramos un Aviso Legal en tu web. Es recomendable incluirlo para cumplir con la legislación.'
                    },
                    {
                        key: 'privacy_policy',
                        label: 'Política de Privacidad',
                        positive: 'Detectamos una Política de Privacidad en tu web.',
                        negative: 'No encontramos una Política de Privacidad. Es obligatoria si recoges datos personales.'
                    },
                    {
                        key: 'cookies_policy',
                        label: 'Política de Cookies',
                        positive: 'Detectamos información sobre Cookies en tu web.',
                        negative: 'No encontramos una Política de Cookies. Es obligatoria si utilizas cookies no esenciales.'
                    },
                    {
                        key: 'has_forms',
                        label: 'Formularios de contacto o suscripción',
                        positive: 'Detectamos formularios en tu web. Asegúrate de mencionar el tratamiento de datos en tu Política de Privacidad.',
                        negative: 'No detectamos formularios en tu web.'
                    },
                    {
                        key: 'uses_google_analytics',
                        label: 'Google Analytics',
                        positive: 'Detectamos que usas Google Analytics. Debes informar sobre esto en tu Política de Cookies.',
                        negative: 'No detectamos Google Analytics en tu web.'
                    },
                    {
                        key: 'uses_facebook_pixel',
                        label: 'Facebook Pixel',
                        positive: 'Detectamos Facebook Pixel en tu web. Debes informar sobre esto en tu Política de Cookies.',
                        negative: 'No detectamos Facebook Pixel en tu web.'
                    },
                    {
                        key: 'has_checkout',
                        label: 'Funcionalidad de compra',
                        positive: 'Detectamos elementos de checkout o compra. Recuerda incluir los Términos y Condiciones de venta.',
                        negative: 'No detectamos elementos de checkout o compra en tu web.'
                    }
                ];
                
                // Crear y añadir cada resultado
                items.forEach(item => {
                    const result = data.results[item.key];
                    const bgColor = result ? 'bg-green-50' : 'bg-red-50';
                    const borderColor = result ? 'border-green-200' : 'border-red-200';
                    const icon = result ? '✅' : '❌';
                    const text = result ? item.positive : item.negative;
                    
                    const html = `
                    <div class="flex p-4 rounded-lg border ${bgColor} ${borderColor}">
                        <div class="text-2xl mr-4">${icon}</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-1">${item.label}</h4>
                            <p class="text-gray-600">${text}</p>
                        </div>
                    </div>`;
                    
                    resultsContainer.innerHTML += html;
                });
                
                // Mostrar sección de resultados y ocultar formulario
                formSection.classList.add('hidden');
                resultsSection.classList.remove('hidden');
                
                // Scroll al inicio de los resultados
                resultsSection.scrollIntoView({ behavior: 'smooth' });
            }
            
            // Botón para analizar otra web
            newAnalysisButton.addEventListener('click', function() {
                resultsSection.classList.add('hidden');
                formSection.classList.remove('hidden');
                urlInput.value = '';
                urlInput.focus();
            });
            
            // Botón para generar textos legales
            generateButton.addEventListener('click', function() {
                showAlert('info', 'La generación de textos legales estará disponible próximamente.');
            });
            
            // Función para mostrar errores en el formulario
            function showError(element, message) {
                element.textContent = message;
                element.parentElement.querySelector('input').focus();
            }
            
            // Función para mostrar alertas
            function showAlert(type, message) {
                const colors = {
                    error: 'bg-red-100 border-red-400 text-red-700',
                    success: 'bg-green-100 border-green-400 text-green-700',
                    info: 'bg-blue-100 border-blue-400 text-blue-700',
                    warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
                };
                
                const html = `
                <div class="${colors[type]} px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline">${message}</span>
                    <button type="button" class="absolute right-0 top-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span>&times;</span>
                    </button>
                </div>`;
                
                alertContainer.innerHTML = html;
                
                // Scroll al inicio para ver la alerta
                window.scrollTo(0, 0);
            }
            
            // Limpiar error al escribir en el campo
            urlInput.addEventListener('input', function() {
                urlError.textContent = '';
            });
        });
    </script>
</body>
</html>
