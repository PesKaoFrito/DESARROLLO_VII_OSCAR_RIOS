<?php
/**
 * Script de Inicialización de Datos (Seed)
 * Ejecutar después de las migraciones para poblar la base de datos con datos iniciales
 */

require_once 'config.php';
require_once 'src/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "🌱 Iniciando seed de datos iniciales...\n\n";
    
    // 1. Insertar Roles
    echo "📝 Insertando roles...\n";
    $roles = [
        ['admin', 'Administrador del sistema con acceso completo'],
        ['supervisor', 'Supervisor de reclamos, puede asignar y revisar'],
        ['analyst', 'Analista de reclamos, crea y gestiona reclamos']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO roles (name, description) VALUES (?, ?)");
    foreach ($roles as $role) {
        $stmt->execute($role);
    }
    echo "✓ Roles insertados\n\n";
    
    // 2. Insertar Categorías
    echo "📝 Insertando categorías...\n";
    $categories = [
        ['Auto', 'Reclamos relacionados con seguros de vehículos'],
        ['Hogar', 'Reclamos de seguros de propiedad y hogar'],
        ['Vida', 'Reclamos de seguros de vida'],
        ['Salud', 'Reclamos médicos y de salud'],
        ['Robo', 'Reclamos por robo o hurto'],
        ['Incendio', 'Reclamos por daños causados por fuego']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO categories (name, description) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    echo "✓ Categorías insertadas\n\n";
    
    // 3. Insertar Estados
    echo "📝 Insertando estados...\n";
    $statuses = [
        ['pending', 'Pendiente de revisión inicial'],
        ['in-review', 'En proceso de revisión por analista'],
        ['approved', 'Reclamo aprobado para pago'],
        ['rejected', 'Reclamo rechazado'],
        ['closed', 'Caso cerrado']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO statuses (name, description) VALUES (?, ?)");
    foreach ($statuses as $status) {
        $stmt->execute($status);
    }
    echo "✓ Estados insertados\n\n";
    
    // 4. Insertar Decisiones
    echo "📝 Insertando decisiones...\n";
    $decisions = [
        ['approved', 'Reclamo aprobado completamente'],
        ['rejected', 'Reclamo rechazado - no cumple requisitos'],
        ['partial', 'Aprobación parcial del monto reclamado'],
        ['requires-info', 'Requiere información adicional']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO decisions (name, description) VALUES (?, ?)");
    foreach ($decisions as $decision) {
        $stmt->execute($decision);
    }
    echo "✓ Decisiones insertadas\n\n";
    
    // 5. Insertar Usuario Administrador por defecto
    echo "📝 Creando usuario administrador...\n";
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT IGNORE INTO users (id, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([999, 'Administrador', 'admin@sistema.com', $adminPassword, 'admin']);
    echo "✓ Usuario admin creado\n";
    echo "   Email: admin@sistema.com\n";
    echo "   Password: admin123\n";
    echo "   ⚠️  Cambiar contraseña después del primer login\n\n";
    
    // 6. Insertar Pólizas de Ejemplo (opcional)
    echo "📝 Insertando pólizas de ejemplo...\n";
    $policies = [
        [
            'POL-2025-00001',
            'Juan Pérez García',
            'juan.perez@email.com',
            '6000-0000',
            'Calle 50, Ciudad de Panamá',
            'Auto',
            50000.00,
            1200.00,
            '2025-01-01',
            '2026-01-01',
            'active'
        ],
        [
            'POL-2025-00002',
            'María González López',
            'maria.gonzalez@email.com',
            '6100-1111',
            'Avenida Balboa, Panamá',
            'Hogar',
            75000.00,
            850.00,
            '2025-01-15',
            '2026-01-15',
            'active'
        ]
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO policies (policy_number, insured_name, insured_email, insured_phone, insured_address, policy_type, coverage_amount, premium_amount, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($policies as $policy) {
        try {
            $stmt->execute($policy);
        } catch (PDOException $e) {
            // Ignorar duplicados
        }
    }
    echo "✓ Pólizas de ejemplo insertadas\n\n";
    
    echo "✅ ¡Seed completado exitosamente!\n\n";
    echo "🚀 El sistema está listo para usar.\n";
    echo "🌐 Accede a: " . BASE_URL . "\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error durante el seed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
