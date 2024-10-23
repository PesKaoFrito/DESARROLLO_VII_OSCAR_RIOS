<?php
include 'login.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
function imprimirCalificaciones($estudiantes,$alumno){
    foreach ($estudiantes as $estudiante) {
        if ($estudiante['rol']=== "estudiante" && $estudiante['user']=== $alumno) {
            echo "{$estudiante['user']}"."\n"."{$estudiante['calificaciones']}"."\n\n";
        }
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
    <h2>Bienvenido, Estudiante <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Esta es tu área personal.</p>
    <p> Tus calificaciones son las siguientes: </p>
    <?php
        imprimirCalificaciones($usuarios,$_SESSION['usuario'])
    ?>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>