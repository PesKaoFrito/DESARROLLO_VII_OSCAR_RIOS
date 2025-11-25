<?php
/**
 * Claims Module - Router
 * Maneja las rutas bonitas: /claims, /claims/create, /claims/edit/123, etc.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/Claim.php';
require_once __DIR__ . '/ClaimManager.php';
require_once __DIR__ . '/../Policies/PolicyManager.php';
require_once __DIR__ . '/../Categories/CategoryManager.php';
require_once __DIR__ . '/../Statuses/StatusManager.php';
require_once __DIR__ . '/../Users/UserManager.php';

requireAuth();

// Obtener la acción de la URL (list, create, edit, view)
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$categoryManager = new CategoryManager();
$statusManager = new StatusManager();

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
            setFlashMessage('error', 'ID de reclamo no especificado');
            redirectTo(BASE_URL . 'claims');
        }
        require __DIR__ . '/views/edit.php';
        break;
    
    case 'view':
        if (!$id) {
            setFlashMessage('error', 'ID de reclamo no especificado');
            redirectTo(BASE_URL . 'claims');
        }
        require __DIR__ . '/views/view.php';
        break;
    
    case 'delete':
        if (!$id) {
            setFlashMessage('error', 'ID de reclamo no especificado');
            redirectTo(BASE_URL . 'claims');
        }
        if ($claimManager->deleteClaim($id)) {
            setFlashMessage('success', 'Reclamo eliminado exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el reclamo');
        }
        redirectTo(BASE_URL . 'claims');
        break;
    
    default:
        setFlashMessage('error', 'Acción no válida');
        redirectTo(BASE_URL . 'claims');
        break;
}
