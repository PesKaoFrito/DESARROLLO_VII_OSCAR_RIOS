<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema de Inventario de Productos' ?></title>
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/assets/css/app.css">
</head>
<body>
    <?php if (isset($showNav) && $showNav): ?>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="<?= url('dashboard.php') ?>" class="navbar-brand">👨‍💻 Sistema de Productos</a>
            <div class="navbar-menu">
                <a href="<?= url('index.php') ?>">🏠 Inicio</a>
                <a href="<?= url('./src/productos') ?>">📱 Productos</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="main-container">
        <?php echo $content; ?>
    </main>
</body>
</html>