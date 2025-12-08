<?php
<<<<<<< HEAD
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
        <p>Estado y métricas de pólizas</p>
    </a>
    
    <a href="financial-report.php" class="action-card">
        <i class="fas fa-dollar-sign"></i>
        <h3>Reporte Financiero</h3>
        <p>Análisis de montos y pagos</p>
    </a>
    
    <a href="performance-report.php" class="action-card">
        <i class="fas fa-chart-line"></i>
        <h3>Rendimiento</h3>
        <p>Métricas de desempeño del equipo</p>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Información</h3>
    </div>
    <div class="card-body">
        <p>Los reportes están en desarrollo. Selecciona un tipo de reporte para comenzar el análisis.</p>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../views/layout.php';
?>
=======
/**
 * Reports Module - Router
 * Maneja las rutas bonitas: /reports, /reports/claims, /reports/policies, etc.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Claims/ClaimManager.php';
require_once __DIR__ . '/../Policies/PolicyManager.php';

requireAuth();

// Obtener la acción de la URL (index es el dashboard principal)
$action = $_GET['action'] ?? 'index';

// Enrutar según la acción
switch ($action) {
    case 'index':
    case 'list':
        require __DIR__ . '/views/index.php';
        break;
    
    case 'claims':
        require __DIR__ . '/views/claims.php';
        break;
    
    case 'policies':
        require __DIR__ . '/views/policies.php';
        break;
    
    case 'export':
        require __DIR__ . '/views/export.php';
        break;
    
    default:
        setFlashMessage('error', 'Reporte no encontrado');
        redirectTo(BASE_URL . 'reports');
        break;
}
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
