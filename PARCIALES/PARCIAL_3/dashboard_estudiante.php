<?php
session_start();
require_once 'config.php';

// Validar que sea estudiante
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'estudiante') {
    header('Location: login.php');
    exit;
}

// Buscar datos del estudiante actual
$estudianteActual = null;
foreach ($usuarios as $user) {
    if ($user['user'] === $_SESSION['user'] && $user['rol'] === 'estudiante') {
        $estudianteActual = $user;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Estudiante</title>
</head>
<body>
    <h2>Bienvenido, Estudiante <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
    <p>Esta es tu área personal.</p>
    <h2>Mis Calificaciones</h2>
    <?php if ($estudianteActual): ?>
        <p><strong>Usuario:</strong> <?php echo $estudianteActual['user']; ?></p>
        <p><strong>Calificaciones:</strong> <?php echo implode(", ", $estudianteActual['calificaciones']); ?></p>
        <p><strong>Promedio:</strong> <?php 
            $promedio = count($estudianteActual['calificaciones']) > 0 
                ? round(array_sum($estudianteActual['calificaciones']) / count($estudianteActual['calificaciones']), 2)
                : 0;
            echo $promedio;
        ?></p>
    <?php endif; ?>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>