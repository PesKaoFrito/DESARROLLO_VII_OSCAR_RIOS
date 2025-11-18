<?php
session_start();
require_once 'config.php';
require_once 'libros.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar y sanitizar datos
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
        $autor = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
        $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_SPECIAL_CHARS);
        $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

        // Validar que todos los campos necesarios estén presentes
        if (!$id || !$titulo || !$autor || !$isbn || $cantidad === false) {
            throw new Exception("Todos los campos son obligatorios");
        }

        $libros = new Libros();
        
        // Verificar si el libro existe antes de actualizar
        $libroExistente = $libros->obtenerPorId($id);
        if (!$libroExistente) {
            throw new Exception("El libro no existe");
        }

        $resultado = $libros->actualizar($id, $titulo, $autor, $isbn, date('Y'), $cantidad);

        if ($resultado) {
            $_SESSION['mensaje'] = "Libro actualizado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            throw new Exception("No se realizaron cambios en el libro");
        }
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

header('Location: index.php');
exit;