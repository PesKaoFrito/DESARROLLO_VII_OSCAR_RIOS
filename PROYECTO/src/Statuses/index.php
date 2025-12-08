<?php
require_once __DIR__ . '/..config.php';
require_once __DIR__ . '/..includes/auth.php';
require_once __DIR__ . '/..includes/helpers.php';
require_once __DIR__ . '/..src/Database.php';
require_once __DIR__ . '/StatusManager.php';

requireAuth();
requireRole(['admin', 'supervisor']);

$statusManager = new StatusManager();
$statuses = $statusManager->getAllStatuses();

$pageTitle = 'Estados - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-toggle-on"></i> Gestión de Estados</h1>
        <p class="subtitle">Administra los estados de reclamos</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Estados</h3>
        <span class="badge badge-info"><?= count($statuses) ?> estados</span>
    </div>
    <div class="card-body">
        <?php if (empty($statuses)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay estados registrados</h3>
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
                        <?php foreach ($statuses as $status): ?>
                        <tr>
                            <td><?= $status['id'] ?></td>
                            <td>
                                <span class="badge badge-<?= $status['name'] ?>">
                                    <?= htmlspecialchars($status['name']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($status['description'] ?? 'N/A') ?></td>
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
