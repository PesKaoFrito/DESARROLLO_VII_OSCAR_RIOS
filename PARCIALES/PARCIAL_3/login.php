<?php
session_start();
require_once 'config.php';

if(isset($_SESSION['user'])) {
    switch ($_SESSION['rol']) {
        case 'estudiante':
            header("Location: dashboard_estudiante.php");
            break;
        case 'profesor':
            header("Location: dashboard_profesor.php");
            break;
    }
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $usuario = $_POST['user'];
        $contrasena = $_POST['password'];
        $error = null;
        $loginExitoso = false;

        foreach ($usuarios as $user) {
            if($usuario === $user['user'] && $contrasena === $user['password']) {
                $_SESSION['user'] = $usuario;
                $_SESSION['rol'] = $user['rol'];
                $loginExitoso = true;
                break;
            }
        }
        if (strlen($usuario)< 3){
            $error="Introduzca un usuario que tenga 3 caracteres o más";
        }
        else if (strlen($contrasena)<5) {
            $error="Introduzca una contraseña que tenga 5 carácteres o más";
        }
        else if ($loginExitoso) {
            if ($_SESSION['rol'] === "estudiante") {
                header("Location: dashboard_estudiante.php");
            }   else if ($_SESSION['rol'] === "profesor") {
            header("Location: dashboard_profesor.php");
            }
            exit();  
        }
        else{
            $error="Introduzca un usuario y contraseña válidos";
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
    <input type="text" name="user" id="user" required> 
    <label for="password">Contraseña</label>
    <input type="password" name="password" id="password" required> 
    <input type="submit" value="Entrar">
    </form>
</body>
</html>