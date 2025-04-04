    </main>
    
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-6 px-4 overflow-hidden sm:px-6 lg:px-8">
            <div class="mt-4 flex justify-center space-x-6">
                <a href="/about" class="text-gray-400 hover:text-gray-500">
                    <?= __('app.about') ?>
                </a>

                <a href="/contact" class="text-gray-400 hover:text-gray-500">
                    <?= __('app.contact') ?>
                </a>

                <a href="/privacy" class="text-gray-400 hover:text-gray-500">
                    <?= __('app.privacy_policy') ?>
                </a>

                <a href="/terms" class="text-gray-400 hover:text-gray-500">
                    <?= __('app.terms_of_service') ?>
                </a>
            </div>
            <p class="mt-4 text-center text-gray-400">&copy; <?= date('Y') ?> LegalityFrame. <?= __('app.all_rights_reserved') ?></p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Script para mensajes flash
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('[role="alert"]');
            
            flashMessages.forEach(message => {
                // Añadir botón de cerrar
                const closeButton = document.createElement('button');
                closeButton.innerHTML = '&times;';
                closeButton.className = 'ml-auto font-bold';
                closeButton.addEventListener('click', () => {
                    message.remove();
                });
                message.prepend(closeButton);
                
                // Auto-ocultar después de 5 segundos
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 1s';
                    
                    setTimeout(() => {
                        message.remove();
                    }, 1000);
                }, 5000);
            });
        });
    </script>
    
    <?php if (isset($footerScripts)): ?>
        <?= $footerScripts ?>
    <?php endif; ?>
</body>
</html> 