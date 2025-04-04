<!DOCTYPE html>
<html lang="<?= config('app.locale') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada - LegalityFrame</title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <h1 class="text-4xl font-bold text-primary-600 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Página no encontrada</h2>
            <p class="text-gray-600 mb-6">Lo sentimos, no hemos podido encontrar la página que estás buscando.</p>
            <a href="<?= url('/') ?>" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded transition-colors">
                Volver a la página de inicio
            </a>
        </div>
    </div>
</body>
</html> 