<?php
/**
 * Script para Recrear la Base de Datos
 * Elimina todas las tablas y las vuelve a crear con las migraciones
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "🗑️  Eliminando tablas existentes...\n\n";
    
    // Deshabilitar foreign key checks temporalmente
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Lista de tablas en orden inverso a las dependencias
    $tables = [
        'claim_files',
        'claims_results',
        'claims',
        'policies',
        'users',
        'decisions',
        'statuses',
        'categories',
        'roles'
    ];
    
    foreach ($tables as $table) {
        try {
            $db->exec("DROP TABLE IF EXISTS $table");
            echo "✓ Tabla '$table' eliminada\n";
        } catch (PDOException $e) {
            echo "⚠️  Advertencia al eliminar '$table': " . $e->getMessage() . "\n";
        }
    }
    
    // Volver a habilitar foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "\n✅ Todas las tablas han sido eliminadas\n\n";
    echo "▶️  Ahora ejecuta:\n";
    echo "   1. php run-migrations.php\n";
    echo "   2. php database/seed.php\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
