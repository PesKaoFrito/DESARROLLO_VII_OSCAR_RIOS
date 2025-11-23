<?php
require_once '../../config.php';
require_once '../../includes/auth.php';
require_once '../../includes/helpers.php';
require_once '../../src/Database.php';
require_once '../../src/Claims/ClaimManager.php';
require_once '../../src/Policies/PolicyManager.php';
require_once '../../src/Users/UserManager.php';
require_once '../../src/Statuses/StatusManager.php';

requireAuth();

$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$statusManager = new StatusManager();

// Filtros
$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Obtener reclamos
if ($searchTerm) {
    $claims = $claimManager->searchClaims($searchTerm);
} else {
    $claims = $claimManager->getAllClaims();
}

// Filtrar por estado si se especifica
if ($statusFilter) {
    $claims = array_filter($claims, fn($c) => $c['status'] === $statusFilter);
}

// Obtener estados disponibles
$statuses = $statusManager->getAllStatuses();

$pageTitle = 'Reclamos - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .page-header h1 {
        color: #333;
    }
    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .filters-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    .claims-section {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    .table tr:hover {
        background: #f8f9fa;
    }
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    .badge-approved {
        background: #d4edda;
        color: #155724;
    }
    .badge-rejected {
        background: #f8d7da;
        color: #721c24;
    }
    .badge-in-review {
        background: #d1ecf1;
        color: #0c5460;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .no-data {
        text-align: center;
        padding: 3rem;
        color: #666;
    }
</style>

<div class="page-header">
    <h1>📋 Gestión de Reclamos</h1>
    <a href="create.php" class="btn btn-primary">➕ Nuevo Reclamo</a>
</div>

<div class="filters-section">
    <form method="GET" class="filters-form">
        <div class="filter-group">
            <label for="search">Buscar</label>
            <input type="text" id="search" name="search" placeholder="Número, asegurado..." value="<?= htmlspecialchars($searchTerm) ?>">
        </div>
        <div class="filter-group">
            <label for="status">Estado</label>
            <select id="status" name="status">
                <option value="">Todos</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status['name'] ?>" <?= $statusFilter === $status['name'] ? 'selected' : '' ?>>
                        <?= ucfirst($status['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group" style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Filtrar</button>
        </div>
        <?php if ($searchTerm || $statusFilter): ?>
        <div class="filter-group" style="display: flex; align-items: flex-end;">
            <a href="index.php" class="btn btn-secondary" style="width: 100%;">🔄 Limpiar</a>
        </div>
        <?php endif; ?>
    </form>
</div>

<div class="claims-section">
    <?php if (empty($claims)): ?>
        <div class="no-data">
            <p style="font-size: 3rem;">📋</p>
            <p>No se encontraron reclamos</p>
            <a href="create.php" class="btn btn-primary" style="margin-top: 1rem;">Crear el primero</a>
        </div>
    <?php else: ?>
        <table class="table">
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
                    <td><?= htmlspecialchars($claim['category']) ?></td>
                    <td><?= formatMoney($claim['amount']) ?></td>
                    <td>
                        <span class="badge badge-<?= $claim['status'] ?>">
                            <?= ucfirst($claim['status']) ?>
                        </span>
                    </td>
                    <td>ID: <?= $claim['analyst_id'] ?></td>
                    <td><?= formatDate($claim['created_at']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="view.php?id=<?= $claim['id'] ?>" class="btn btn-sm btn-primary">👁️ Ver</a>
                            <a href="edit.php?id=<?= $claim['id'] ?>" class="btn btn-sm btn-secondary">✏️ Editar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require '../../views/layout.php';
?>
