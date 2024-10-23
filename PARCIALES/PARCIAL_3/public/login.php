<?php

$usuarios=[
    ["user"=> "oscar1", "password"=> "ejemplo", "rol"=> "estudiante", "calificaciones"=> [100, 75, 80]],
    ["user" => "jesus1", "password"=> "sample", "rol"=> "estudiante", "calificaciones"=> [90, 73, 86]],
    ["user"=> "eduardo1", "password"=> "admin123", "rol" => "profesor", "calificaciones" => []],
];
session_start();

if(isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['user'];
        $contrasena = $_POST['password'];

    foreach ($usuarios as $user) {
        if($usuario === $user['user'] && $contrasena === $user['password']) {
            $_SESSION['user'] = $usuario;
            if ($user['rol']==="estudiante") {
                header("Location: dashboard_estudiante.php");
            }
            else if ($user['rol']==="profesor") {
                header("Location: dasboard_profesor.php");
            }
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
    <form action="" method="post">
    <label for="user">Usuario</label>
    <input type="text" name="user" id="user"> 
    <label for="password">Contraseña</label>
    <input type="password" name="password" id="password"> 
    <input type="submit" value="Entrar">
    </form>
</body>
</html>