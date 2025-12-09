<?php
require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/helpers.php';
require_once 'src/Database.php';
require_once 'src/Claims/ClaimManager.php';
require_once 'src/Policies/PolicyManager.php';
require_once 'src/Users/UserManager.php';

// Requerir autenticación
requireAuth();

$currentUser = getCurrentUser();
$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$userManager = new UserManager();

// Obtener estadísticas según el rol
$recentClaims = [];
$stats = [
    'total_claims' => 0,
    'pending_claims' => 0,
    'approved_claims' => 0,
    'rejected_claims' => 0,
    'total_policies' => 0,
    'active_policies' => 0
];

try {
    // Obtener reclamos según el rol del usuario
    if ($currentUser['role'] === 'admin') {
        $allClaims = $claimManager->getAllClaims();
    } elseif ($currentUser['role'] === 'supervisor') {
        $allClaims = $claimManager->getClaimsBySupervisor($currentUser['id']);
    } else { // analyst
        $allClaims = $claimManager->getClaimsByAnalyst($currentUser['id']);
    }
    
    $stats['total_claims'] = count($allClaims);
    $recentClaims = array_slice($allClaims, 0, 5);
    
    // Contar por estado
    foreach ($allClaims as $claim) {
        if ($claim['status_name'] === 'pending') $stats['pending_claims']++;
        if ($claim['status_name'] === 'approved') $stats['approved_claims']++;
        if ($claim['status_name'] === 'rejected') $stats['rejected_claims']++;
    }
    
    // Estadísticas de pólizas (solo admin y supervisor ven todas)
    if ($currentUser['role'] === 'admin' || $currentUser['role'] === 'supervisor') {
        $policyStats = $policyManager->getPolicyStats();
        $stats['total_policies'] = $policyStats['total'] ?? 0;
        $stats['active_policies'] = $policyStats['active'] ?? 0;
    }
    
} catch (Exception $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}

$pageTitle = 'Dashboard - Sistema de Reclamos';
$showNav = true;

ob_start();
?>

<!-- Hero Section -->
<div class="hero-section" style="margin-bottom: 3rem;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Bienvenido, <?= $currentUser['name'] ?></h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">Panel de Control - <?= translateRole($currentUser['role']) ?></p>
        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= url('claims/create') ?>" class="btn btn-light" style="padding: 0.75rem 2rem;">
                <i class="fas fa-plus"></i> Nuevo Reclamo
            </a>
            <a href="<?= url('reports') ?>" class="btn btn-light" style="padding: 0.75rem 2rem;">
                <i class="fas fa-chart-bar"></i> Ver Reportes
            </a>
        </div>
    </div>
</div>

<div class="page-header">
    <h2><i class="fas fa-chart-line"></i> Estadísticas Generales</h2>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <?= $error ?>
    </div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['total_claims'] ?></div>
            <div class="stat-label">Total Reclamos</div>
        </div>
    </div>
    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['pending_claims'] ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
    </div>
    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['approved_claims'] ?></div>
            <div class="stat-label">Aprobados</div>
        </div>
    </div>
    <div class="stat-card stat-danger">
        <div class="stat-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['rejected_claims'] ?></div>
            <div class="stat-label">Rechazados</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-file-alt"></i> Reclamos Recientes</h3>
        <a href="<?= url('claims') ?>" class="btn btn-primary">
            <i class="fas fa-list"></i> Ver Todos
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($recentClaims)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No hay reclamos registrados</h3>
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
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentClaims as $claim): ?>
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
                                    <?= translateStatus($claim['status_name']) ?>
                                </span>
                            </td>
                            <td><?= formatDate($claim['created_at']) ?></td>
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
require 'views/layout.php';
?>
