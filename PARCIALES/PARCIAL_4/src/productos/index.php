<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ .'/../Database.php';
require_once __DIR__ .'./Producto.php';
require_once __DIR__ .'./ProductoManager.php';

// Obtener la acción de la URL (list, create, edit, view)
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$productoManager= new ProductoManager();

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
            setFlashMessage('error', 'ID de producto no especificado');
            redirectTo(BASE_URL . '/src/productos');
        }
        require __DIR__ . '/views/edit.php';
        break;
    
    case 'view':
        if (!$id) {
            setFlashMessage('error', 'ID de producto no especificado');
            redirectTo(BASE_URL . '/src/productos');
        }
        require __DIR__ . '/views/view.php';
        break;
    
    case 'delete':
        if (!$id) {
            setFlashMessage('error', 'ID de producto no especificado');
            redirectTo(BASE_URL . '/src/productos');
        }
        if ($claimManager->deleteClaim($id)) {
            setFlashMessage('success', 'producto eliminado exitosamente');
        } else {
            setFlashMessage('error', 'Error al eliminar el producto');
        }
        redirectTo(BASE_URL . '/src/productos');
        break;
    
    default:
        setFlashMessage('error', 'Acción no válida');
        redirectTo(BASE_URL . '/src/productos');
        break;
}
?>