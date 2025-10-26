<?php
session_start();
require_once 'config.php';
require_once 'libros.php';

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID no proporcionado");
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        throw new Exception("ID de libro inválido");
    }

    $libros = new Libros();
    
    // Verificar si el libro existe
    $libroExistente = $libros->obtenerPorId($id);
    if (!$libroExistente) {
        throw new Exception("El libro no existe");
    }

    // Verificar si tiene préstamos activos
    $db = Database::getInstance();
    $query = "SELECT COUNT(*) as total FROM prestamos WHERE libro_id = ? AND estado = 'activo'";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $prestamosActivos = $result->fetch_assoc()['total'];

    if ($prestamosActivos > 0) {
        throw new Exception("No se puede eliminar el libro porque tiene préstamos activos");
    }

    // Proceder con la eliminación
    $resultado = $libros->eliminar($id);

    if ($resultado) {
        $_SESSION['mensaje'] = "Libro eliminado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception("No se pudo eliminar el libro");
    }
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

header('Location: index.php');
exit;