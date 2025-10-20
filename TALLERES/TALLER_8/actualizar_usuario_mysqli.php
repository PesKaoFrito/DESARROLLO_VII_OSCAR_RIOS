<?php
    require_once "config_mysqli.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);

            if (mysqli_stmt_execute($stmt)) {
                echo "Usuario actualizado con éxito.";
            } else {
                echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }
?>