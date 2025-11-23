<?php
require_once '../../config.php';
require_once '../../includes/auth.php';
require_once '../../includes/helpers.php';
require_once '../../src/Database.php';
require_once '../../src/Claims/ClaimManager.php';
require_once '../../src/Policies/PolicyManager.php';

requireAuth();

$claimManager = new ClaimManager();
$policyManager = new PolicyManager();

// Obtener datos para reportes
$allClaims = $claimManager->getAllClaims();
$policyStats = $policyManager->getPolicyStats();

// Estadísticas de reclamos
$stats = [
    'total' => count($allClaims),
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0,
    'in_review' => 0,
    'total_amount' => 0,
    'avg_amount' => 0
];

$claimsByCategory = [];
$claimsByMonth = [];

foreach ($allClaims as $claim) {
    // Contar por estado
    if (isset($stats[$claim['status']])) {
        $stats[$claim['status']]++;
    }
    
    // Sumar montos
    $stats['total_amount'] += $claim['amount'];
    
    // Agrupar por categoría
    $cat = $claim['category'];
    if (!isset($claimsByCategory[$cat])) {
        $claimsByCategory[$cat] = ['count' => 0, 'amount' => 0];
    }
    $claimsByCategory[$cat]['count']++;
    $claimsByCategory[$cat]['amount'] += $claim['amount'];
    
    // Agrupar por mes
    $month = date('Y-m', strtotime($claim['created_at']));
    if (!isset($claimsByMonth[$month])) {
        $claimsByMonth[$month] = 0;
    }
    $claimsByMonth[$month]++;
}

if ($stats['total'] > 0) {
    $stats['avg_amount'] = $stats['total_amount'] / $stats['total'];
}

$pageTitle = 'Reportes y Estadísticas';
$showNav = true;

ob_start();
?>

<style>
    .page-header {
        margin-bottom: 2rem;
    }
    .page-header h1 {
        color: #333;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
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
    .report-section {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .report-section h2 {
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #667eea;
    }
    .category-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .category-item:last-child {
        border-bottom: none;
    }
    .category-name {
        font-weight: 600;
        color: #333;
    }
    .category-stats {
        display: flex;
        gap: 2rem;
        color: #666;
    }
    .chart-bar {
        background: #f0f0f0;
        height: 30px;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    .chart-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        padding: 0 1rem;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .export-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>

<div class="page-header">
    <h1>📈 Reportes y Estadísticas</h1>
    <p style="color: #666;">Análisis de siniestralidad y gestión de reclamos</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Total Reclamos</div>
        <div class="stat-value"><?= $stats['total'] ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Pendientes</div>
        <div class="stat-value"><?= $stats['pending'] ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Aprobados</div>
        <div class="stat-value"><?= $stats['approved'] ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">❌</div>
        <div class="stat-label">Rechazados</div>
        <div class="stat-value"><?= $stats['rejected'] ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Monto Total</div>
        <div class="stat-value" style="font-size: 1.5rem;"><?= formatMoney($stats['total_amount']) ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Promedio</div>
        <div class="stat-value" style="font-size: 1.5rem;"><?= formatMoney($stats['avg_amount']) ?></div>
    </div>
</div>

<div class="report-section">
    <h2>📊 Reclamos por Categoría</h2>
    <?php if (empty($claimsByCategory)): ?>
        <p style="text-align: center; color: #666;">No hay datos para mostrar</p>
    <?php else: ?>
        <?php
        arsort($claimsByCategory);
        $maxCount = max(array_column($claimsByCategory, 'count'));
        ?>
        <?php foreach ($claimsByCategory as $category => $data): ?>
            <div class="category-item">
                <div>
                    <div class="category-name"><?= htmlspecialchars($category) ?></div>
                    <div class="chart-bar" style="max-width: 600px; margin-top: 0.5rem;">
                        <div class="chart-fill" style="width: <?= ($data['count'] / $maxCount * 100) ?>%;">
                            <?= $data['count'] ?> reclamos
                        </div>
                    </div>
                </div>
                <div class="category-stats">
                    <div>
                        <strong><?= formatMoney($data['amount']) ?></strong>
                        <div style="font-size: 0.85rem;">Monto total</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="report-section">
    <h2>📅 Reclamos por Mes</h2>
    <?php if (empty($claimsByMonth)): ?>
        <p style="text-align: center; color: #666;">No hay datos para mostrar</p>
    <?php else: ?>
        <?php
        ksort($claimsByMonth);
        $maxMonthCount = max($claimsByMonth);
        ?>
        <?php foreach ($claimsByMonth as $month => $count): ?>
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                    <strong><?= date('F Y', strtotime($month . '-01')) ?></strong>
                    <span><?= $count ?> reclamos</span>
                </div>
                <div class="chart-bar">
                    <div class="chart-fill" style="width: <?= ($count / $maxMonthCount * 100) ?>%;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="report-section">
    <h2>📄 Estadísticas de Pólizas</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: #667eea;"><?= $policyStats['total'] ?? 0 ?></div>
            <div style="color: #666;">Total Pólizas</div>
        </div>
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: #28a745;"><?= $policyStats['active'] ?? 0 ?></div>
            <div style="color: #666;">Activas</div>
        </div>
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: #dc3545;"><?= $policyStats['expired'] ?? 0 ?></div>
            <div style="color: #666;">Vencidas</div>
        </div>
        <div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;"><?= formatMoney($policyStats['total_coverage'] ?? 0) ?></div>
            <div style="color: #666;">Cobertura Total</div>
        </div>
    </div>
</div>

<div class="export-buttons">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir Reporte</button>
    <button onclick="alert('Funcionalidad de exportación a implementar')" class="btn btn-secondary">📥 Exportar a Excel</button>
</div>

<?php
$content = ob_get_clean();
require '../../views/layout.php';
?>
