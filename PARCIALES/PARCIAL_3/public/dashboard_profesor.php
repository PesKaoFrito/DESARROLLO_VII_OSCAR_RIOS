<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

function imprimirCalificaciones($estudiantes){
    foreach ($estudiantes as $estudiante) {
        if ($estudiante['rol']=== "estudiante") {
            echo "{$estudiante['user']}"."\n"."{$estudiante['calificaciones']}"."\n\n";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Profesor</title>
</head>
<body>
    <h2>Bienvenido, Profesor <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Esta es tu área personal.</p>
    <?php
        imprimirCalificaciones($usuarios);
    ?>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>