<?php
session_start();

// Cargar variables de entorno desde .env
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios y líneas vacías
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Verificar que la línea tenga un =
        if (strpos($line, '=') === false) {
            continue;
        }
        
        // Parsear línea
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remover comillas si existen
        $value = trim($value, '"\'');

        // Establecer variable de entorno
        if (!empty($name)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Cargar el archivo .env
loadEnv(__DIR__ . '/.env');

// Configuración general
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost/PROYECTO/');
define('PUBLIC_URL', BASE_URL . 'public');

// Configuración de la base de datos usando variables de entorno
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'utp_proyecto_final');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');

// Cargar clases necesarias
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

// Inicializar la base de datos
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}