<?php
/**
 * Script de Inicialización de Datos (Seed)
 * Ejecutar después de las migraciones para poblar la base de datos con datos iniciales
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Database.php';

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
        ['Auto', 'Reclamos relacionados con seguros de vehículos', 'accidentes'],
        ['Hogar', 'Reclamos de seguros de propiedad y hogar', 'hogar'],
        ['Vida', 'Reclamos de seguros de vida', 'vida'],
        ['Salud', 'Reclamos médicos y de salud', 'salud'],
        ['Robo', 'Reclamos por robo o hurto', 'accidentes'],
        ['Incendio', 'Reclamos por daños causados por fuego', 'hogar']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO categories (name, description, policy_type) VALUES (?, ?, ?)");
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
    
    // 5. Insertar Usuarios por defecto
    echo "📝 Creando usuarios del sistema...\n";
    $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
    
    $users = [
        [1, 'Administrador Sistema', 'admin@sistema.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
        [2, 'Roberto Supervisor', 'roberto.supervisor@sistema.com', $defaultPassword, 'supervisor'],
        [3, 'Laura Supervisor', 'laura.supervisor@sistema.com', $defaultPassword, 'supervisor'],
        [4, 'Carlos Analista', 'carlos.analista@sistema.com', $defaultPassword, 'analyst'],
        [5, 'María Analista', 'maria.analista@sistema.com', $defaultPassword, 'analyst'],
        [6, 'José Analista', 'jose.analista@sistema.com', $defaultPassword, 'analyst'],
        [7, 'Ana Analista', 'ana.analista@sistema.com', $defaultPassword, 'analyst']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO users (id, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    
    echo "✓ Usuarios creados:\n";
    echo "   Admin: admin@sistema.com / admin123\n";
    echo "   Supervisores: roberto.supervisor@sistema.com, laura.supervisor@sistema.com\n";
    echo "   Analistas: carlos.analista@sistema.com, maria.analista@sistema.com, jose.analista@sistema.com, ana.analista@sistema.com\n";
    echo "   Password para supervisores y analistas: password123\n";
    echo "   ⚠️  Cambiar contraseñas después del primer login\n\n";
    
    // 7. Insertar Pólizas de Ejemplo (opcional)
    echo "📝 Insertando pólizas de ejemplo...\n";
    $policies = [
        [
            'POL-2025-00001',
            'Juan Pérez García',
            'juan.perez@email.com',
            '6000-0000',
            'Calle 50, Ciudad de Panamá',
            'accidentes',
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
            'hogar',
            75000.00,
            850.00,
            '2025-01-15',
            '2026-01-15',
            'active'
        ],
        [
            'POL-2025-00003',
            'Carlos Rodríguez Soto',
            'carlos.rodriguez@email.com',
            '6200-2222',
            'Vía España, Panamá',
            'salud',
            100000.00,
            2500.00,
            '2025-02-01',
            '2026-02-01',
            'active'
        ],
        [
            'POL-2025-00004',
            'Ana Martínez Cruz',
            'ana.martinez@email.com',
            '6300-3333',
            'Costa del Este, Panamá',
            'vida',
            200000.00,
            3000.00,
            '2025-03-01',
            '2026-03-01',
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
    
    // Obtener IDs necesarios
    $policyId = $db->query("SELECT id FROM policies LIMIT 1")->fetchColumn() ?: 1;
    $categoryAutoId = $db->query("SELECT id FROM categories WHERE name = 'Auto' LIMIT 1")->fetchColumn() ?: 1;
    $categoryHomeId = $db->query("SELECT id FROM categories WHERE name = 'Hogar' LIMIT 1")->fetchColumn() ?: 2;
    $categoryHealthId = $db->query("SELECT id FROM categories WHERE name = 'Salud' LIMIT 1")->fetchColumn() ?: 4;
    $statusPendingId = $db->query("SELECT id FROM statuses WHERE name = 'pending' LIMIT 1")->fetchColumn() ?: 1;
    $statusReviewId = $db->query("SELECT id FROM statuses WHERE name = 'in-review' LIMIT 1")->fetchColumn() ?: 2;
    $statusApprovedId = $db->query("SELECT id FROM statuses WHERE name = 'approved' LIMIT 1")->fetchColumn() ?: 3;
    $analystId = $db->query("SELECT id FROM users WHERE role = 'analyst' LIMIT 1")->fetchColumn() ?: 4;
    $supervisorId = $db->query("SELECT id FROM users WHERE role = 'supervisor' LIMIT 1")->fetchColumn() ?: 2;
    
    $claims = [
        [
            'CLM-2025-00001',
            $policyId,
            $categoryAutoId,
            $statusPendingId,
            'Juan Pérez García',
            '6234-5678',
            'juan.perez@email.com',
            5000.00,
            'Colisión en intersección, daños en parte frontal del vehículo',
            $analystId,
            $supervisorId
        ],
        [
            'CLM-2025-00002',
            $policyId,
            $categoryHomeId,
            $statusReviewId,
            'María González López',
            '6345-6789',
            'maria.gonzalez@email.com',
            8500.00,
            'Daños por inundación en el primer piso de la vivienda',
            $analystId,
            $supervisorId
        ],
        [
            'CLM-2025-00003',
            $policyId,
            $categoryHealthId,
            $statusApprovedId,
            'Carlos López Díaz',
            '6456-7890',
            'carlos.lopez@email.com',
            12000.00,
            'Cirugía de emergencia por apendicitis aguda',
            $analystId,
            $supervisorId
        ]
    ];
    
    $checkClaim = $db->prepare("SELECT id FROM claims WHERE claim_number = ?");
    $insertClaim = $db->prepare("INSERT INTO claims (claim_number, policy_id, category_id, status_id, insured_name, insured_phone, insured_email, amount, description, analyst_id, supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($claims as $claim) {
        $checkClaim->execute([$claim[0]]);
        if (!$checkClaim->fetch()) {
            $insertClaim->execute($claim);
            echo "   ✓ Reclamo creado: {$claim[0]} - {$claim[4]}\n";
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
    echo "      Email: roberto.supervisor@sistema.com\n";
    echo "      Password: password123\n\n";
    echo "   📊 Analistas:\n";
    echo "      Email: carlos.analista@sistema.com\n";
    echo "      Email: ana.analista@sistema.com\n";
    echo "      Password: password123\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🌐 Accede a: " . BASE_URL . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error durante el seed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
