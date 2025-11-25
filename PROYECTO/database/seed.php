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
    
    // Verificar si ya existe el usuario admin
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute(['admin@sistema.com']);
    
    if (!$checkStmt->fetch()) {
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrador', 'admin@sistema.com', $adminPassword, 'admin']);
        echo "✓ Usuario admin creado (ID: " . $db->lastInsertId() . ")\n";
    } else {
        echo "⚠️  Usuario admin ya existe\n";
    }
    
    echo "   Email: admin@sistema.com\n";
    echo "   Password: admin123\n";
    echo "   ⚠️  Cambiar contraseña después del primer login\n\n";
    
    // 6. Insertar Usuarios de Ejemplo (Analista y Supervisor)
    echo "📝 Creando usuarios de ejemplo...\n";
    $exampleUsers = [
        ['Carlos Rodríguez', 'carlos.rodriguez@sistema.com', 'analyst'],
        ['Ana Martínez', 'ana.martinez@sistema.com', 'supervisor'],
        ['Pedro Sánchez', 'pedro.sanchez@sistema.com', 'analyst']
    ];
    
    $password = password_hash('password123', PASSWORD_DEFAULT);
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $insertStmt = $db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    
    foreach ($exampleUsers as $user) {
        $checkStmt->execute([$user[1]]);
        if (!$checkStmt->fetch()) {
            $insertStmt->execute([$user[0], $user[1], $password, $user[2]]);
            echo "   ✓ Usuario creado: {$user[0]} ({$user[2]})\n";
        }
    }
    echo "   Password para todos: password123\n\n";
    
    // 7. Insertar Pólizas de Ejemplo (opcional)
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
    
    // 8. Insertar Reclamos de Ejemplo
    echo "📝 Insertando reclamos de ejemplo...\n";
    
    // Obtener IDs de usuarios
    $analystStmt = $db->prepare("SELECT id FROM users WHERE role = 'analyst' LIMIT 1");
    $analystStmt->execute();
    $analyst = $analystStmt->fetch();
    $analystId = $analyst ? $analyst['id'] : 1;
    
    $supervisorStmt = $db->prepare("SELECT id FROM users WHERE role = 'supervisor' LIMIT 1");
    $supervisorStmt->execute();
    $supervisor = $supervisorStmt->fetch();
    $supervisorId = $supervisor ? $supervisor['id'] : null;
    
    $claims = [
        [
            'CLM-2025-00001',
            'Juan Pérez García',
            'Auto',
            5000.00,
            'pending',
            $analystId,
            $supervisorId
        ],
        [
            'CLM-2025-00002',
            'María González López',
            'Hogar',
            8500.00,
            'in-review',
            $analystId,
            $supervisorId
        ],
        [
            'CLM-2025-00003',
            'Carlos López Díaz',
            'Salud',
            12000.00,
            'approved',
            $analystId,
            $supervisorId
        ]
    ];
    
    $checkClaim = $db->prepare("SELECT id FROM claims WHERE claim_number = ?");
    $insertClaim = $db->prepare("INSERT INTO claims (claim_number, insured_name, category, amount, status, analyst_id, supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($claims as $claim) {
        $checkClaim->execute([$claim[0]]);
        if (!$checkClaim->fetch()) {
            $insertClaim->execute($claim);
            echo "   ✓ Reclamo creado: {$claim[0]} - {$claim[1]}\n";
        }
    }
    echo "✓ Reclamos de ejemplo insertados\n\n";
    
    echo "✅ ¡Seed completado exitosamente!\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🚀 El sistema está listo para usar\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "👤 CREDENCIALES DE ACCESO:\n\n";
    echo "   🔑 Administrador:\n";
    echo "      Email: admin@sistema.com\n";
    echo "      Password: admin123\n\n";
    echo "   👔 Supervisor:\n";
    echo "      Email: ana.martinez@sistema.com\n";
    echo "      Password: password123\n\n";
    echo "   📊 Analistas:\n";
    echo "      Email: carlos.rodriguez@sistema.com\n";
    echo "      Email: pedro.sanchez@sistema.com\n";
    echo "      Password: password123\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🌐 Accede a: " . BASE_URL . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error durante el seed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
