<?php
session_start();
require_once 'config.php';

// Validar que sea profesor
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Profesor</title>
</head>
<body>
    <h2>Bienvenido, Profesor <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
    <p>Esta es tu área personal.</p>
    <?php
        if (isset($usuarioActual['calificaciones'])) {
            echo "Tus calificaciones: " . implode(", ", $usuarioActual['calificaciones']);
        }
    ?>
    <h2>Lista de Estudiantes</h2>
    <table border="1">
        <tr>
            <th>Usuario</th>
            <th>Calificaciones</th>
            <th>Promedio</th>
        </tr>
        <?php
        foreach ($usuarios as $user) {
            if ($user['rol'] === 'estudiante') {
                $promedio = count($user['calificaciones']) > 0 
                    ? round(array_sum($user['calificaciones']) / count($user['calificaciones']), 2)
                    : 0;
                echo "<tr>";
                echo "<td>" . $user['user'] . "</td>";
                echo "<td>" . implode(", ", $user['calificaciones']) . "</td>";
                echo "<td>" . $promedio . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>