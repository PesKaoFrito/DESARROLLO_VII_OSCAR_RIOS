<?php
session_start();
require_once 'config.php';
require_once 'libros.php';

try {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        throw new Exception("ID de libro inválido");
    }

    $libros = new Libros();
    $resultado = $libros->eliminar($id);

    if ($resultado) {
        $_SESSION['mensaje'] = "Libro eliminado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception("No se pudo eliminar el libro");
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

header('Location: index.php');
exit;