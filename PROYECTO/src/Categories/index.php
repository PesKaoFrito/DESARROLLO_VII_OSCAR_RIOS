<?php
require_once __DIR__ . '/..config.php';
require_once __DIR__ . '/..includes/auth.php';
require_once __DIR__ . '/..includes/helpers.php';
require_once __DIR__ . '/..src/Database.php';
require_once __DIR__ . '/CategoryManager.php';

requireAuth();
requireRole(['admin', 'supervisor']);

$categoryManager = new CategoryManager();
$categories = $categoryManager->getAllCategories();

$pageTitle = 'Categorías - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-tags"></i> Gestión de Categorías</h1>
        <p class="subtitle">Administra las categorías de reclamos</p>
    </div>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Categoría
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Categorías</h3>
        <span class="badge badge-info"><?= count($categories) ?> categorías</span>
    </div>
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay categorías registradas</h3>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                            <td><?= htmlspecialchars($category['description'] ?? 'N/A') ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
require __DIR__ . '/..views/layout.php';
?>
