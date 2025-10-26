<?php
session_start();
require_once 'config.php';
require_once 'libros.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar y sanitizar datos
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $datos = [
            'titulo' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
            'autor' => filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS),
            'isbn' => filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_SPECIAL_CHARS),
            'cantidad_disponible' => filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT)
        ];

        // Validar que todos los campos necesarios estén presentes
        if (!$id || in_array(false, $datos, true) || in_array(null, $datos, true)) {
            throw new Exception("Datos de entrada inválidos");
        }

        $libros = new Libros();
        $resultado = $libros->actualizar($id, $datos);

        if ($resultado) {
            $_SESSION['mensaje'] = "Libro actualizado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            throw new Exception("No se realizaron cambios en el libro");
        }
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