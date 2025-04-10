<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Textos Legales con IA</title>
    <!-- Tailwind CSS via CDN (versión específica) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Script personalizado -->
    <script src="assets/script.js" defer></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Generador de Textos Legales con IA</h1>
            <p class="text-xl text-gray-600">Analiza tu sitio web y genera documentos legales personalizados</p>
        </header>

        <main>
            <!-- Contenedor para mensajes de error (gestionado por JavaScript) -->
            <div id="error-container"></div>
            
            <section class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ingresa la URL de tu sitio web</h2>
                <p class="text-gray-600 mb-6">Analizaremos tu sitio web para detectar qué documentos legales necesitas.</p>

                <form id="url-form" action="analyze.php" method="POST" onsubmit="return validateURLBeforeSubmit()">
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
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition transform hover:-translate-y-0.5">
                            Analizar sitio web
                        </button>
                    </div>
                </form>
            </section>

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
</body>
</html>
