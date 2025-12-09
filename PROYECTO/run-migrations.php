<?php
// Script simple para ejecutar migraciones en orden estricto
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Desactivar verificación de claves foráneas temporalmente
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    $migrations = [
        '001_create_roles_table.sql',
        '002_create_categories_table.sql',
        '003_create_decisions_table.sql',
        '004_create_statuses_table.sql',
        '005_create_policies_table.sql',
        '006_create_users_table.sql',
        '007_create_claims_table.sql',
        '008_create_claimsresults_table.sql',
        '009_create_claimfiles_table.sql'
    ];
    
    foreach ($migrations as $migration) {
        $file = __DIR__ . '/database/migrations/' . $migration;
        
        if (!file_exists($file)) {
            echo "⚠️  Archivo no encontrado: $migration\n";
            continue;
        }
        
        $sql = file_get_contents($file);
        
        try {
            $pdo->exec($sql);
            echo "✅ $migration\n";
        } catch (PDOException $e) {
            echo "❌ Error en $migration: " . $e->getMessage() . "\n";
        }
    }
    
    // Reactivar verificación de claves foráneas
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "\n✅ Migraciones completadas\n";
    
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage() . "\n");
}
