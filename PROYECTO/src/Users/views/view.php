<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../UserManager.php';

requireAuth();
requireRole(['admin', 'supervisor']);

$userId = $_GET['id'] ?? 0;
$userManager = new UserManager();
$user = $userManager->getUserById($userId);

if (!$user) {
    $_SESSION['error_message'] = 'Usuario no encontrado';
    redirectTo('src/Users/');
}

$pageTitle = 'Detalle del Usuario - ' . $user['name'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-user"></i> <?= htmlspecialchars($user['name']) ?></h1>
        <p class="subtitle">Información del usuario</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <?php if (hasRole('admin')): ?>
        <a href="<?= url('users/edit?id=' . $user['id']) ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <?php endif; ?>
        <a href="<?= url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Información del Usuario</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-hashtag"></i> ID</label>
                <value><?= $user['id'] ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-user"></i> Nombre</label>
                <value><?= htmlspecialchars($user['name']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-envelope"></i> Email</label>
                <value><?= htmlspecialchars($user['email']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-user-tag"></i> Rol</label>
                <value>
                    <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'supervisor' ? 'warning' : 'info') ?>">
                        <?= translateRole($user['role']) ?>
                    </span>
                </value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar-plus"></i> Fecha de Registro</label>
                <value><?= formatDate($user['created_at']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar-check"></i> Última Actualización</label>
                <value><?= formatDate($user['updated_at']) ?></value>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
