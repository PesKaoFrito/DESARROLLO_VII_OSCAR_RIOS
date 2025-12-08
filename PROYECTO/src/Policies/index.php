<?php
<<<<<<< HEAD
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../src/Database.php';
=======
/**
 * Policies Module - Router
 * Maneja las rutas bonitas: /policies, /policies/create, /policies/edit/123, etc.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/Policy.php';
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
require_once __DIR__ . '/PolicyManager.php';

requireAuth();

<<<<<<< HEAD
$policyManager = new PolicyManager();

// Filtros
$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Obtener pólizas
if ($searchTerm) {
    $policies = $policyManager->searchPolicies($searchTerm);
} else {
    $policies = $policyManager->getAllPolicies();
}

// Filtrar por estado si se especifica
if ($statusFilter) {
    $policies = array_filter($policies, fn($p) => $p['status'] === $statusFilter);
}

$pageTitle = 'Pólizas - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-contract"></i> Gestión de Pólizas</h1>
        <p class="subtitle">Administra todas las pólizas del sistema</p>
    </div>
    <a href="<?= url('policies/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Póliza
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label for="search">Buscar</label>
                <input type="text" id="search" name="search" placeholder="Número de póliza, titular..." value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
            <div class="filter-group">
                <label for="status">Estado</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Activa</option>
                    <option value="expired" <?= $statusFilter === 'expired' ? 'selected' : '' ?>>Expirada</option>
                    <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>
            <div class="filter-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <?php if ($searchTerm || $statusFilter): ?>
                <a href="<?= url('policies') ?>" class="btn btn-secondary" style="flex: 1;">
                    <i class="fas fa-redo"></i> Limpiar
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Listado de Pólizas</h3>
        <span class="badge badge-info"><?= count($policies) ?> pólizas</span>
    </div>
    <div class="card-body">
        <?php if (empty($policies)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No se encontraron pólizas</h3>
                <p>Comienza creando tu primera póliza</p>
                <a href="<?= url('policies/create') ?>" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Crear Póliza
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Titular</th>
                            <th>Tipo</th>
                            <th>Cobertura</th>
                            <th>Prima</th>
                            <th>Estado</th>
                            <th>Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($policies as $policy): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($policy['policy_number']) ?></strong></td>
                            <td><?= htmlspecialchars($policy['insured_name']) ?></td>
                            <td>
                                <span class="badge badge-secondary">
                                    <?= ucfirst($policy['policy_type']) ?>
                                </span>
                            </td>
                            <td><strong><?= formatMoney($policy['coverage_amount']) ?></strong></td>
                            <td><?= formatMoney($policy['premium_amount']) ?></td>
                            <td>
                                <span class="badge badge-<?= $policy['status'] ?>">
                                    <?= ucfirst($policy['status']) ?>
                                </span>
                            </td>
                            <td><?= formatDate($policy['end_date']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= url('policies/view?id=' . $policy['id']) ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('policies/edit?id=' . $policy['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
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
=======
// Obtener la acción de la URL (list, create, edit, view)
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$policyManager = new PolicyManager();

// Enrutar según la acción
switch ($action) {
    case 'list':
        require __DIR__ . '/views/list.php';
        break;
    
    case 'create':
        require __DIR__ . '/views/create.php';
        break;
    
    case 'edit':
        if (!$id) {
            setFlashMessage('error', 'ID de póliza no especificado');
            redirectTo(BASE_URL . 'policies');
        }
        require __DIR__ . '/views/edit.php';
        break;
    
    case 'view':
        if (!$id) {
            setFlashMessage('error', 'ID de póliza no especificado');
            redirectTo(BASE_URL . 'policies');
        }
        require __DIR__ . '/views/view.php';
        break;
    
    case 'delete':
        if (!$id) {
            setFlashMessage('error', 'ID de póliza no especificado');
            redirectTo(BASE_URL . 'policies');
        }
        if ($policyManager->deletePolicy($id)) {
            setFlashMessage('success', 'Póliza eliminada exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar la póliza');
        }
        redirectTo(BASE_URL . 'policies');
        break;
    
    default:
        setFlashMessage('error', 'Acción no válida');
        redirectTo(BASE_URL . 'policies');
        break;
}
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
