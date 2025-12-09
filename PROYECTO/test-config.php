<?php
/**
 * Script de verificación de configuración
 * Ejecutar desde: http://localhost/PROYECTO/test-config.php
 */

// Solo cargar la función loadEnv sin ejecutar el resto del config
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') === false) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        $value = trim($value, '"\'');

        if (!empty($name)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

loadEnv(__DIR__ . '/.env');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Configuración</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .config-item {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .config-label {
            font-weight: bold;
            color: #555;
        }
        .config-value {
            color: #667eea;
            font-family: monospace;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-ok {
            background: #d4edda;
            color: #155724;
        }
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Verificación de Configuración</h1>
        
        <div class="config-item">
            <div class="config-label">BASE_URL:</div>
            <div class="config-value"><?= $_ENV['APP_URL'] ?? $_ENV['BASE_URL'] ?? '<span class="status status-error">NO DEFINIDO</span>' ?></div>
        </div>
        
        <div class="config-item">
            <div class="config-label">DB_HOST:</div>
            <div class="config-value"><?= $_ENV['DB_HOST'] ?? '<span class="status status-error">NO DEFINIDO</span>' ?></div>
        </div>
        
        <div class="config-item">
            <div class="config-label">DB_NAME:</div>
            <div class="config-value"><?= $_ENV['DB_DATABASE'] ?? $_ENV['DB_NAME'] ?? '<span class="status status-error">NO DEFINIDO</span>' ?></div>
        </div>
        
        <div class="config-item">
            <div class="config-label">DB_USER:</div>
            <div class="config-value"><?= $_ENV['DB_USERNAME'] ?? $_ENV['DB_USER'] ?? '<span class="status status-error">NO DEFINIDO</span>' ?></div>
        </div>
        
        <div class="config-item">
            <div class="config-label">DB_PASS:</div>
            <div class="config-value"><?= empty($_ENV['DB_PASSWORD']) && empty($_ENV['DB_PASS']) ? '<em>(vacío)</em>' : '***oculto***' ?></div>
        </div>
        
        <div class="config-item">
            <div class="config-label">DB_PORT:</div>
            <div class="config-value"><?= $_ENV['DB_PORT'] ?? '3306 (por defecto)' ?></div>
        </div>
        
        <hr style="margin: 30px 0; border: none; border-top: 2px dashed #ddd;">
        
        <h2>🔌 Test de Conexión</h2>
        <?php
        try {
            $dbName = $_ENV['DB_DATABASE'] ?? $_ENV['DB_NAME'] ?? 'proyecto_reclamos';
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_PORT'] ?? '3306',
                $dbName
            );
            
            $dbUser = $_ENV['DB_USERNAME'] ?? $_ENV['DB_USER'] ?? 'root';
            $dbPass = $_ENV['DB_PASSWORD'] ?? $_ENV['DB_PASS'] ?? '';
            
            $pdo = new PDO(
                $dsn,
                $dbUser,
                $dbPass
            );
            
            echo '<div class="config-item"><span class="status status-ok">✓ CONEXIÓN EXITOSA</span></div>';
            
        } catch (PDOException $e) {
            echo '<div class="config-item">';
            echo '<span class="status status-error">✗ ERROR DE CONEXIÓN</span><br><br>';
            echo '<strong>Mensaje:</strong> ' . htmlspecialchars($e->getMessage());
            echo '</div>';
            
            echo '<div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">';
            echo '<strong>💡 Posibles soluciones:</strong><br>';
            echo '• Verificar que MySQL/MariaDB esté corriendo en Laragon<br>';
            echo '• Crear la base de datos: <code>CREATE DATABASE utp_proyecto_final;</code><br>';
            echo '• Verificar credenciales en el archivo .env<br>';
            echo '• Ejecutar: <code>php run-migrations.php</code>';
            echo '</div>';
        }
        ?>
        
        <hr style="margin: 30px 0; border: none; border-top: 2px dashed #ddd;">
        
        <div style="text-align: center; color: #999; font-size: 12px;">
            <p><strong>Archivo:</strong> test-config.php</p>
            <p><strong>Eliminar después de verificar</strong></p>
        </div>
    </div>
</body>
</html>
