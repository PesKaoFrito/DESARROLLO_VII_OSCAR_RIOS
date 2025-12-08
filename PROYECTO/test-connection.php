<?php
// Test database connection
echo "=== Test de Conexión a Base de Datos ===\n\n";

// Test 1: Sin contraseña con localhost
try {
    echo "Test 1: Conectando SIN contraseña a localhost...\n";
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Conexión exitosa SIN contraseña a localhost\n";
    
    // Verificar si existe la base de datos
    $stmt = $pdo->query("SHOW DATABASES LIKE 'proyecto_reclamos'");
    $exists = $stmt->fetch();
    if ($exists) {
        echo "✓ Base de datos 'proyecto_reclamos' existe\n";
    } else {
        echo "✗ Base de datos 'proyecto_reclamos' NO existe\n";
        echo "Creando base de datos...\n";
        $pdo->exec("CREATE DATABASE proyecto_reclamos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Base de datos creada\n";
    }
    
    // Intentar conectar a la base de datos específica
    $pdo2 = new PDO(
        "mysql:host=localhost;dbname=proyecto_reclamos",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Conexión exitosa a 'proyecto_reclamos'\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Sin contraseña con 127.0.0.1
try {
    echo "Test 2: Conectando SIN contraseña a 127.0.0.1...\n";
    $pdo = new PDO(
        "mysql:host=127.0.0.1",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Conexión exitosa SIN contraseña a 127.0.0.1\n";
    
    // Verificar base de datos
    $stmt = $pdo->query("SHOW DATABASES LIKE 'proyecto_reclamos'");
    $exists = $stmt->fetch();
    if ($exists) {
        echo "✓ Base de datos 'proyecto_reclamos' existe\n";
    } else {
        echo "✗ Base de datos 'proyecto_reclamos' NO existe\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Con contraseña 'root'
try {
    echo "Test 3: Conectando CON contraseña 'root'...\n";
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        "root",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✓ Conexión exitosa CON contraseña 'root'\n";
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Fin del test ===\n";
