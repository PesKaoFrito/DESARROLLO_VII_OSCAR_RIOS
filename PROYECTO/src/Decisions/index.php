<?php
require_once __DIR__ . '/..config.php';
require_once __DIR__ . '/..includes/auth.php';
require_once __DIR__ . '/..includes/helpers.php';
require_once __DIR__ . '/..src/Database.php';
require_once __DIR__ . '/DecisionManager.php';

requireAuth();
requireRole(['admin', 'supervisor']);

$decisionManager = new DecisionManager();
$decisions = $decisionManager->getAllDecisions();

$pageTitle = 'Decisiones - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-gavel"></i> Gestión de Decisiones</h1>
        <p class="subtitle">Administra los tipos de decisiones</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Decisiones</h3>
        <span class="badge badge-info"><?= count($decisions) ?> decisiones</span>
    </div>
    <div class="card-body">
        <?php if (empty($decisions)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay decisiones registradas</h3>
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
                        <?php foreach ($decisions as $decision): ?>
                        <tr>
                            <td><?= $decision['id'] ?></td>
                            <td><strong><?= htmlspecialchars($decision['name']) ?></strong></td>
                            <td><?= htmlspecialchars($decision['description'] ?? 'N/A') ?></td>
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
