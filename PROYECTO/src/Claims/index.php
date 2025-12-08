<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/ClaimManager.php';
require_once __DIR__ . '/../Policies/PolicyManager.php';
require_once __DIR__ . '/../Users/UserManager.php';
require_once __DIR__ . '/../Statuses/StatusManager.php';

requireAuth();

$currentUser = getCurrentUser();
$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$statusManager = new StatusManager();

// Filtros
$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Obtener reclamos según el rol del usuario
if ($searchTerm) {
    $claims = $claimManager->searchClaims($searchTerm);
    // Filtrar resultados de búsqueda según el rol
    if ($currentUser['role'] === 'analyst') {
        $claims = array_filter($claims, fn($c) => $c['analyst_id'] == $currentUser['id']);
    } elseif ($currentUser['role'] === 'supervisor') {
        $claims = array_filter($claims, fn($c) => $c['supervisor_id'] == $currentUser['id']);
    }
} else {
    // Obtener reclamos según el rol
    if ($currentUser['role'] === 'admin') {
        $claims = $claimManager->getAllClaims();
    } elseif ($currentUser['role'] === 'supervisor') {
        $claims = $claimManager->getClaimsBySupervisor($currentUser['id']);
    } else { // analyst
        $claims = $claimManager->getClaimsByAnalyst($currentUser['id']);
    }
}

// Filtrar por estado si se especifica
if ($statusFilter) {
    $claims = array_filter($claims, fn($c) => $c['status_name'] === $statusFilter);
}

// Obtener estados disponibles
$statuses = $statusManager->getAllStatuses();

$pageTitle = 'Reclamos - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-alt"></i> Gestión de Reclamos</h1>
        <p class="subtitle">Administra todos los reclamos del sistema</p>
    </div>
    <a href="<?= url('claims/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Reclamo
    </a>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="filters-form">
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label for="search">Buscar Reclamo</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           class="form-control"
                           placeholder="Número de reclamo, nombre del asegurado..." 
                           value="<?= htmlspecialchars($searchTerm) ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="status">Filtrar por Estado</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos los estados</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['name'] ?>" <?= $statusFilter === $status['name'] ? 'selected' : '' ?>>
                                <?= ucfirst($status['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <?php if ($searchTerm || $statusFilter): ?>
                    <a href="<?= url('claims') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Reclamos</h3>
        <span class="badge badge-info"><?= count($claims) ?> reclamos</span>
    </div>
    <div class="card-body">
        <?php if (empty($claims)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No se encontraron reclamos</h3>
                <p>Comienza creando tu primer reclamo</p>
                <a href="<?= url('claims/create') ?>" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Crear Reclamo
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Asegurado</th>
                            <th>Categoría</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Analista</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($claim['claim_number']) ?></strong></td>
                            <td><?= htmlspecialchars($claim['insured_name']) ?></td>
                            <td>
                                <span class="badge badge-secondary">
                                    <?= htmlspecialchars($claim['category_name']) ?>
                                </span>
                            </td>
                            <td><strong><?= formatMoney($claim['amount']) ?></strong></td>
                            <td>
                                <span class="badge badge-<?= $claim['status_name'] ?>">
                                    <?= ucfirst($claim['status_name']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($claim['analyst_name'] ?? 'Sin asignar') ?></td>
                            <td><?= formatDate($claim['created_at']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= url('claims/view?id=' . $claim['id']) ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('claims/edit?id=' . $claim['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
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
require __DIR__ . '/../../views/layout.php';
?>
