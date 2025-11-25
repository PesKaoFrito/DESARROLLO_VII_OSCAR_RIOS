<?php
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
