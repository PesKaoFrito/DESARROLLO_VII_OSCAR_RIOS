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
    // Obtener reclamos recientes
    $allClaims = $claimManager->getAllClaims();
    $stats['total_claims'] = count($allClaims);
    $recentClaims = array_slice($allClaims, 0, 5);
    
    // Contar por estado
    foreach ($allClaims as $claim) {
        if ($claim['status'] === 'pending') $stats['pending_claims']++;
        if ($claim['status'] === 'approved') $stats['approved_claims']++;
        if ($claim['status'] === 'rejected') $stats['rejected_claims']++;
    }
    
    // Estadísticas de pólizas
    $policyStats = $policyManager->getPolicyStats();
    $stats['total_policies'] = $policyStats['total'] ?? 0;
    $stats['active_policies'] = $policyStats['active'] ?? 0;
    
} catch (Exception $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}

$pageTitle = 'Dashboard - Sistema de Reclamos';
$showNav = true;

ob_start();
?>

<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }
    .dashboard-header h1 {
        color: #333;
        margin-bottom: 0.5rem;
    }
    .welcome-text {
        color: #666;
        font-size: 1.1rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
    }
    .stat-card.pending {
        border-left-color: #ffa500;
    }
    .stat-card.approved {
        border-left-color: #28a745;
    }
    .stat-card.rejected {
        border-left-color: #dc3545;
    }
    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
    }
    .section-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .section-header h2 {
        color: #333;
        font-size: 1.5rem;
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
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }
    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        text-decoration: none;
        color: #333;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .action-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .action-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .action-desc {
        font-size: 0.875rem;
        color: #666;
    }
</style>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p class="welcome-text">Bienvenido, <?= $currentUser['name'] ?> (<?= ucfirst($currentUser['role']) ?>)</p>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Reclamos</div>
        <div class="stat-value"><?= $stats['total_claims'] ?></div>
    </div>
    <div class="stat-card pending">
        <div class="stat-label">Pendientes</div>
        <div class="stat-value"><?= $stats['pending_claims'] ?></div>
    </div>
    <div class="stat-card approved">
        <div class="stat-label">Aprobados</div>
        <div class="stat-value"><?= $stats['approved_claims'] ?></div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-label">Rechazados</div>
        <div class="stat-value"><?= $stats['rejected_claims'] ?></div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h2>📋 Reclamos Recientes</h2>
        <a href="modules/claims/index.php" class="btn btn-primary">Ver Todos</a>
    </div>
    
    <?php if (empty($recentClaims)): ?>
        <p>No hay reclamos registrados aún.</p>
    <?php else: ?>
        <table class="table">
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
                    <td><?= htmlspecialchars($claim['category']) ?></td>
                    <td><?= formatMoney($claim['amount']) ?></td>
                    <td>
                        <span class="badge badge-<?= $claim['status'] ?>">
                            <?= ucfirst($claim['status']) ?>
                        </span>
                    </td>
                    <td><?= formatDate($claim['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="quick-actions">
    <a href="modules/claims/create.php" class="action-card">
        <div class="action-icon">➕</div>
        <div class="action-title">Nuevo Reclamo</div>
        <div class="action-desc">Registrar un nuevo reclamo</div>
    </a>
    
    <a href="modules/policies/index.php" class="action-card">
        <div class="action-icon">📄</div>
        <div class="action-title">Pólizas</div>
        <div class="action-desc"><?= $stats['active_policies'] ?> pólizas activas</div>
    </a>
    
    <a href="modules/reports/index.php" class="action-card">
        <div class="action-icon">📊</div>
        <div class="action-title">Reportes</div>
        <div class="action-desc">Ver estadísticas y reportes</div>
    </a>
    
    <?php if (hasRole('admin') || hasRole('supervisor')): ?>
    <a href="modules/users/index.php" class="action-card">
        <div class="action-icon">👥</div>
        <div class="action-title">Usuarios</div>
        <div class="action-desc">Gestionar usuarios del sistema</div>
    </a>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require 'views/layout.php';
?>
