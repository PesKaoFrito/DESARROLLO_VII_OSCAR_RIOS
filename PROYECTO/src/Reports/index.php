<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Reports/ReportManager.php';

requireAuth();

$pageTitle = 'Reportes - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-chart-bar"></i> Reportes y Estadísticas</h1>
        <p class="subtitle">Análisis y métricas del sistema</p>
    </div>
</div>

<div class="quick-actions-grid">
    <a href="claims-report.php" class="action-card">
        <i class="fas fa-file-alt"></i>
        <h3>Reporte de Reclamos</h3>
        <p>Análisis detallado de reclamos por período</p>
    </a>
    
    <a href="policies-report.php" class="action-card">
        <i class="fas fa-file-contract"></i>
        <h3>Reporte de Pólizas</h3>
        <p>Estado y análisis de pólizas</p>
    </a>
    
    <a href="users-report.php" class="action-card">
        <i class="fas fa-users"></i>
        <h3>Reporte de Usuarios</h3>
        <p>Actividad y gestión de usuarios</p>
    </a>
    
    <a href="financial-report.php" class="action-card">
        <i class="fas fa-dollar-sign"></i>
        <h3>Reporte Financiero</h3>
        <p>Análisis de montos y pagos</p>
    </a>
</div>

<!-- Estadísticas Generales -->
<div class="stats-grid" style="margin-top: 2rem;">
    <?php
    $reportManager = new ReportManager();
    $stats = $reportManager->getGeneralStats();
    ?>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats['total_claims'] ?? 0 ?></h3>
            <p>Total Reclamos</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats['pending_claims'] ?? 0 ?></h3>
            <p>Reclamos Pendientes</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats['approved_claims'] ?? 0 ?></h3>
            <p>Reclamos Aprobados</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <i class="fas fa-file-contract"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats['total_policies'] ?? 0 ?></h3>
            <p>Pólizas Activas</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../views/layout.php';
?>
