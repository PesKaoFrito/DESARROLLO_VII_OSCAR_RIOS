<?php
require_once __DIR__ . '/..config.php';
require_once __DIR__ . '/..includes/auth.php';
require_once __DIR__ . '/..includes/helpers.php';
require_once __DIR__ . '/..src/Database.php';
require_once __DIR__ . '/RoleManager.php';

requireAuth();
requireRole(['admin']);

$roleManager = new RoleManager();
$roles = $roleManager->getAllRoles();

$pageTitle = 'Roles - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-user-tag"></i> Gestión de Roles</h1>
        <p class="subtitle">Administra los roles del sistema</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Roles</h3>
        <span class="badge badge-info"><?= count($roles) ?> roles</span>
    </div>
    <div class="card-body">
        <?php if (empty($roles)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay roles registrados</h3>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $role['id'] ?></td>
                            <td><strong><?= htmlspecialchars($role['name']) ?></strong></td>
                            <td><?= htmlspecialchars($role['description'] ?? 'N/A') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/..views/layout.php';
?>
