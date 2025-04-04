<div class="max-w-5xl mx-auto">
    <section class="text-center py-12">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Cumplimiento legal automatizado para tu sitio web</h1>
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">Analiza, genera y gestiona todos tus documentos legales con inteligencia artificial y olvídate de problemas de cumplimiento.</p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
            <a href="<?= url('/scan') ?>" class="btn btn-primary text-lg py-3 px-8">Analizar mi sitio web</a>
            <a href="<?= url('/pricing') ?>" class="btn btn-secondary text-lg py-3 px-8">Ver planes</a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 max-w-3xl mx-auto">
            <form action="<?= url('/scan') ?>" method="post" class="flex flex-col sm:flex-row gap-2">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="url" name="domain" placeholder="https://www.ejemplo.com" required
                    class="form-input flex-1 py-3 text-lg">
                <button type="submit" class="btn btn-primary py-3 text-lg whitespace-nowrap">
                    Escanear ahora
                </button>
            </form>
        </div>
    </section>
    
    <!-- Características principales -->
    <section class="py-12">
        <h2 class="text-3xl font-bold text-center mb-12">Cumplimiento legal simplificado</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Escaneo automático</h3>
                <p class="text-gray-600">Detectamos automáticamente cookies, formularios y problemas de cumplimiento legal en tu sitio web.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Documentos personalizados</h3>
                <p class="text-gray-600">Generamos documentos legales perfectamente adaptados a tu negocio y sitio web en segundos.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4 text-primary-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Actualizaciones automáticas</h3>
                <p class="text-gray-600">Mantenemos tus documentos actualizados ante cambios legislativos o nuevas cookies en tu web.</p>
            </div>
        </div>
    </section>
</div> 