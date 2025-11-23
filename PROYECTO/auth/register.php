<?php
require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/helpers.php';
require_once '../src/Database.php';
require_once '../src/Users/User.php';
require_once '../src/Users/UserManager.php';

if (isAuthenticated()) {
    redirectTo(BASE_URL . 'dashboard.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validaciones
    if (!validateRequired($name)) {
        $errors[] = 'El nombre es requerido';
    }
    if (!validateEmail($email)) {
        $errors[] = 'Email inválido';
    }
    if (!validateMinLength($password, 6)) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Las contraseñas no coinciden';
    }

    if (empty($errors)) {
        $userManager = new UserManager();
        
        // Verificar si el email ya existe
        if ($userManager->getUserByEmail($email)) {
            $errors[] = 'El email ya está registrado';
        } else {
            // Generar ID único
            $userId = rand(1000, 999999);
            
            $userData = [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'password_hash' => $password,
                'role' => 'analyst'
            ];
            
            $user = new User($userData);
            
            if ($userManager->createUser($user)) {
                $success = true;
                setFlashMessage('success', 'Registro exitoso. Por favor inicia sesión.');
                header('refresh:2;url=login.php');
            } else {
                $errors[] = 'Error al crear el usuario';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Reclamos</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-register:hover {
            background: #5568d3;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>📝 Crear Cuenta</h1>
            <p>Sistema de Gestión de Reclamos</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✓ Registro exitoso. Redirigiendo al login...
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Nombre completo</label>
                <input type="text" id="name" name="name" required value="<?= $_POST['name'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= $_POST['email'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña (mínimo 6 caracteres)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-register">Registrarse</button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
