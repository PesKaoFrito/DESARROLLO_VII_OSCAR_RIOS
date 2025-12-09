<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../PolicyManager.php';

requireAuth();

$policyManager = new PolicyManager();

$errors = [];
$success = false;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validaciones
    if (empty($_POST['policy_number'])) {
        $errors[] = 'El número de póliza es requerido';
    }
    if (empty($_POST['insured_name'])) {
        $errors[] = 'El nombre del asegurado es requerido';
    }
    if (empty($_POST['policy_type'])) {
        $errors[] = 'El tipo de póliza es requerido';
    }
    if (empty($_POST['coverage_amount']) || $_POST['coverage_amount'] <= 0) {
        $errors[] = 'El monto de cobertura debe ser mayor a 0';
    }
    if (empty($_POST['premium_amount']) || $_POST['premium_amount'] <= 0) {
        $errors[] = 'La prima debe ser mayor a 0';
    }
    if (empty($_POST['start_date'])) {
        $errors[] = 'La fecha de inicio es requerida';
    }
    if (empty($_POST['end_date'])) {
        $errors[] = 'La fecha de vencimiento es requerida';
    }
    
    if (empty($errors)) {
        $data = [
            'policy_number' => sanitize($_POST['policy_number']),
            'insured_name' => sanitize($_POST['insured_name']),
            'insured_email' => sanitize($_POST['insured_email']),
            'insured_phone' => sanitize($_POST['insured_phone']),
            'insured_address' => sanitize($_POST['insured_address']),
            'policy_type' => sanitize($_POST['policy_type']),
            'coverage_amount' => (float)$_POST['coverage_amount'],
            'premium_amount' => (float)$_POST['premium_amount'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if ($policyManager->createPolicy($data)) {
            $_SESSION['success_message'] = 'Póliza creada exitosamente';
            redirectTo('src/Policies/');
        } else {
            $errors[] = 'Error al crear la póliza';
        }
    }
}

$pageTitle = 'Nueva Póliza';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle"></i> Crear Nueva Póliza</h1>
        <p class="subtitle">Registra una nueva póliza en el sistema</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Por favor corrija los siguientes errores:</strong>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <!-- Información de la Póliza -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-file-contract"></i> Información de la Póliza</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="policy_number">Número de Póliza <span class="required">*</span></label>
                    <input type="text" id="policy_number" name="policy_number" required
                           value="<?= htmlspecialchars($_POST['policy_number'] ?? '') ?>"
                           placeholder="POL-2025-00001"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="policy_type">Tipo de Póliza <span class="required">*</span></label>
                    <select id="policy_type" name="policy_type" required class="form-control">
                        <option value="">Seleccione un tipo</option>
                        <option value="vida" <?= (isset($_POST['policy_type']) && $_POST['policy_type'] == 'vida') ? 'selected' : '' ?>>Vida</option>
                        <option value="salud" <?= (isset($_POST['policy_type']) && $_POST['policy_type'] == 'salud') ? 'selected' : '' ?>>Salud</option>
                        <option value="accidentes" <?= (isset($_POST['policy_type']) && $_POST['policy_type'] == 'accidentes') ? 'selected' : '' ?>>Accidentes</option>
                        <option value="hogar" <?= (isset($_POST['policy_type']) && $_POST['policy_type'] == 'hogar') ? 'selected' : '' ?>>Hogar</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Fecha de Inicio <span class="required">*</span></label>
                    <input type="date" id="start_date" name="start_date" required
                           value="<?= htmlspecialchars($_POST['start_date'] ?? date('Y-m-d')) ?>"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="end_date">Fecha de Vencimiento <span class="required">*</span></label>
                    <input type="date" id="end_date" name="end_date" required
                           value="<?= htmlspecialchars($_POST['end_date'] ?? date('Y-m-d', strtotime('+1 year'))) ?>"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Estado</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : 'selected' ?>>Activa</option>
                        <option value="expired" <?= (isset($_POST['status']) && $_POST['status'] == 'expired') ? 'selected' : '' ?>>Expirada</option>
                        <option value="cancelled" <?= (isset($_POST['status']) && $_POST['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Asegurado -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Información del Asegurado</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="insured_name">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="insured_name" name="insured_name" required
                           value="<?= htmlspecialchars($_POST['insured_name'] ?? '') ?>"
                           placeholder="Ej: Juan Pérez García"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="insured_email">Email</label>
                    <input type="email" id="insured_email" name="insured_email"
                           value="<?= htmlspecialchars($_POST['insured_email'] ?? '') ?>"
                           placeholder="ejemplo@email.com"
                           class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="insured_phone">Teléfono</label>
                    <input type="tel" id="insured_phone" name="insured_phone"
                           value="<?= htmlspecialchars($_POST['insured_phone'] ?? '') ?>"
                           placeholder="+507 6000-0000"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="insured_address">Dirección</label>
                    <input type="text" id="insured_address" name="insured_address"
                           value="<?= htmlspecialchars($_POST['insured_address'] ?? '') ?>"
                           placeholder="Calle, Ciudad, País"
                           class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- Información Financiera -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-money-bill-wave"></i> Información Financiera</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="coverage_amount">Monto de Cobertura <span class="required">*</span></label>
                    <input type="number" id="coverage_amount" name="coverage_amount" 
                           step="0.01" min="0" required
                           value="<?= htmlspecialchars($_POST['coverage_amount'] ?? '') ?>"
                           placeholder="50000.00"
                           class="form-control">
                    <small class="form-text">Monto máximo cubierto por la póliza</small>
                </div>
                <div class="form-group">
                    <label for="premium_amount">Prima Mensual <span class="required">*</span></label>
                    <input type="number" id="premium_amount" name="premium_amount" 
                           step="0.01" min="0" required
                           value="<?= htmlspecialchars($_POST['premium_amount'] ?? '') ?>"
                           placeholder="1200.00"
                           class="form-control">
                    <small class="form-text">Pago mensual de la póliza</small>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Póliza
        </button>
        <a href="<?= url('policies') ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
