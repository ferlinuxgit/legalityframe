<!DOCTYPE html>
<html lang="<?= config('app.locale', 'es') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>LegalityFrame</title>
    <meta name="description" content="<?= isset($description) ? $description : __('app.default_description') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    
    <?php if (isset($headerScripts)): ?>
        <?= $headerScripts ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50">
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/">
                            <img class="h-8 w-auto" src="<?= asset('img/logo.png') ?>" alt="LegalityFrame">
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if (isset($languageSelector)): ?>
                        <?= $languageSelector ?>
                    <?php else: ?>
                        <?php include APP_PATH . '/Views/partials/language_selector.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <main class="min-h-screen">
        <!-- Contenido principal -->
    </main>
</body>
</html> 