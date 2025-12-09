<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/UserManager.php';

requireAuth();
requireRole(['admin', 'supervisor']);

$userManager = new UserManager();
$users = $userManager->getAllUsers();

$pageTitle = 'Usuarios - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
        <p class="subtitle">Administra los usuarios del sistema</p>
    </div>
    <?php if (hasRole('admin')): ?>
    <a href="<?= url('users/create') ?>" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </a>
    <?php endif; ?>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Usuarios</h3>
        <span class="badge badge-info"><?= count($users) ?> usuarios</span>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay usuarios registrados</h3>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge badge-secondary">
                                    <?= translateRole($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success">Activo</span>
                            </td>
                            <td><?= formatDate($user['created_at']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= url('users/view?id=' . $user['id']) ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (hasRole('admin')): ?>
                                    <a href="<?= url('users/edit?id=' . $user['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
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
require __DIR__ . '/../../views/layout.php';
?>
