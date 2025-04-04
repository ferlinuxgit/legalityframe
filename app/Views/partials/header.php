<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= url('/') ?>" class="text-2xl font-bold text-primary-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                LegalityFrame
            </a>
            
            <!-- Navegación principal -->
            <nav class="hidden md:flex space-x-8">
                <a href="<?= url('/') ?>" class="font-medium text-gray-700 hover:text-primary-600">Inicio</a>
                <a href="<?= url('/about') ?>" class="font-medium text-gray-700 hover:text-primary-600">Acerca de</a>
                <a href="<?= url('/pricing') ?>" class="font-medium text-gray-700 hover:text-primary-600">Precios</a>
                <a href="<?= url('/contact') ?>" class="font-medium text-gray-700 hover:text-primary-600">Contacto</a>
            </nav>
            
            <!-- Botones de acción -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?= url('/login') ?>" class="font-medium text-gray-700 hover:text-primary-600">Iniciar sesión</a>
                    <a href="<?= url('/register') ?>" class="btn btn-primary">Registrarse</a>
                <?php else: ?>
                    <a href="<?= url('/dashboard') ?>" class="font-medium text-gray-700 hover:text-primary-600">Dashboard</a>
                    <form action="<?= url('/logout') ?>" method="post" class="inline">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <button type="submit" class="font-medium text-gray-700 hover:text-primary-600">Cerrar sesión</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Menú móvil (toggle) -->
            <button type="button" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700" aria-label="Menu móvil">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header> 