<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema de Reclamos' ?></title>
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/assets/css/app.css">
</head>
<body>
    <?php if (isset($showNav) && $showNav): ?>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="<?= url('dashboard.php') ?>" class="navbar-brand">🛡️ Sistema de Reclamos</a>
            <div class="navbar-menu">
                <a href="<?= url('dashboard.php') ?>">📊 Dashboard</a>
                <a href="<?= url('claims') ?>">📋 Reclamos</a>
                <a href="<?= url('policies') ?>">📄 Pólizas</a>
                <a href="<?= url('reports') ?>">📈 Reportes</a>
                <?php if (hasRole('admin') || hasRole('supervisor')): ?>
                <a href="<?= url('users') ?>">👥 Usuarios</a>
                <?php endif; ?>
            </div>
            <div class="navbar-user">
                <div class="user-info">
                    <div class="user-name"><?= getCurrentUser()['name'] ?></div>
                    <div class="user-role"><?= ucfirst(getCurrentUser()['role']) ?></div>
                </div>
                <a href="<?= BASE_URL ?>auth/logout.php" class="btn btn-secondary btn-sm">Salir</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="main-container">
        <?php echo $content; ?>
    </main>
    
    <script src="<?= PUBLIC_URL ?>/assets/js/main.js"></script>
</body>
</html>