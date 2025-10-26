<?php
require_once 'config.php';
require_once 'libros.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar datos de entrada
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
        $autor = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
        $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_SPECIAL_CHARS);
        $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

        if (!$titulo || !$autor || !$isbn || $cantidad === false) {
            throw new Exception("Datos de entrada inválidos");
        }

        $libros = new Libros();
        $resultado = $libros->crear($titulo, $autor, $isbn, date('Y'), $cantidad);

        if ($resultado) {
            $_SESSION['mensaje'] = "Libro agregado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        }
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

// Redirigir de vuelta al índice
header('Location: index.php');
exit;