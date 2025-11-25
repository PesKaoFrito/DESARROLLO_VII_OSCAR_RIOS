<?php
/**
 * Policies Module - Router
 * Maneja las rutas bonitas: /policies, /policies/create, /policies/edit/123, etc.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/Policy.php';
require_once __DIR__ . '/PolicyManager.php';

requireAuth();

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
