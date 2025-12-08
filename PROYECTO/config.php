<?php

// Function to read .env file
function loadEnv($path) {
    if(!file_exists($path)) {
        throw new Exception(".env file not found");
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        // Skip empty lines
        if (empty(trim($line))) {
            continue;
        }
        
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Check if line contains '='
        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remove quotes if present
        if (strlen($value) >= 2 && $value[0] === '"' && $value[strlen($value)-1] === '"') {
            $value = substr($value, 1, -1);
        }
        
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

// Define constants using environment variables
define('BASE_URL', getenv('APP_URL') ?: 'http://localhost/PROYECTO');
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_DATABASE') ?: 'proyecto_reclamos');
define('DB_USER', getenv('DB_USERNAME') ?: 'root');
define('DB_PASS', getenv('DB_PASSWORD') ?: '');

// Derived constants
define('PUBLIC_URL', BASE_URL . '/public');

// You can add more configuration settings here