<!DOCTYPE html>
<html lang="<?= config('app.locale') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LegalityFrame' ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
    <?php if (isset($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= asset($style) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50 font-sans">
    
    <!-- Header -->
    <?php include base_path('app/Views/partials/header.php'); ?>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <?php include base_path('app/Views/partials/footer.php'); ?>
    
    <!-- CSRF Token -->
    <script>
        const CSRF_TOKEN = '<?= $_SESSION['csrf_token'] ?? '' ?>';
    </script>
    
    <!-- Scripts -->
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= asset($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 