<?php
require_once "config_mysqli.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $sql = "DELETE FROM usuarios WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Usuario eliminado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
        }
    }

    mysqli_stmt_close($stmt);
}
?>