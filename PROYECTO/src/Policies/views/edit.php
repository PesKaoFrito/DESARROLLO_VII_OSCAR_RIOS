<?php
/**
 * Policies - Edit View
 * URL: /policies/edit/123
 */

$policy = $policyManager->getPolicyById($id);

if (!$policy) {
    setFlashMessage('error', 'Póliza no encontrada');
    redirectTo(url('policies'));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos requeridos
    $requiredFields = ['policy_number', 'insured_name', 'insured_email', 'insured_phone', 
                       'policy_type', 'coverage_amount', 'premium_amount', 'start_date', 'end_date'];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "El campo " . str_replace('_', ' ', $field) . " es requerido";
        }
    }

    // Validar email
    if (!empty($_POST['insured_email']) && !filter_var($_POST['insured_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido";
    }

    // Validar fechas
    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        if (strtotime($_POST['end_date']) <= strtotime($_POST['start_date'])) {
            $errors[] = "La fecha de fin debe ser posterior a la fecha de inicio";
        }
    }

    if (empty($errors)) {
        $data = [
            'policy_number' => $_POST['policy_number'],
            'insured_name' => $_POST['insured_name'],
            'insured_email' => $_POST['insured_email'],
            'insured_phone' => $_POST['insured_phone'],
            'insured_address' => $_POST['insured_address'] ?? '',
            'policy_type' => $_POST['policy_type'],
            'coverage_amount' => $_POST['coverage_amount'],
            'premium_amount' => $_POST['premium_amount'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'status' => 'active',
            'description' => $_POST['description'] ?? ''
        ];

        if ($policyManager->updatePolicy($id, $data)) {
            setFlashMessage('success', 'Póliza actualizada exitosamente');
            redirectTo(url('policies/view/' . $id));
        } else {
            $errors[] = 'Error al actualizar la póliza';
        }
    }
}

$pageTitle = 'Editar Póliza';
$showNav = true;

ob_start();
?>

<style>
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 0 auto;
    }
    .form-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-label .required {
        color: #dc3545;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
    }
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e0e0e0;
    }
    .error-list {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1.5rem;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1>✏️ Editar Póliza</h1>
        <p style="color: #666; margin-top: 0.5rem;"><?= htmlspecialchars($policy['policy_number']) ?></p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="error-list">
        <strong>⚠️ Errores encontrados:</strong>
        <ul style="margin: 0; padding-left: 1.5rem;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('policies/edit/' . $id) ?>">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Número de Póliza <span class="required">*</span></label>
                <input type="text" name="policy_number" class="form-input" 
                       value="<?= htmlspecialchars($policy['policy_number']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tipo de Póliza <span class="required">*</span></label>
                <select name="policy_type" class="form-select" required>
                    <?php
                    $types = ['Vida', 'Salud', 'Auto', 'Hogar', 'Responsabilidad Civil', 'Viaje', 'Otro'];
                    foreach ($types as $type):
                    ?>
                        <option value="<?= $type ?>" <?= $policy['policy_type'] === $type ? 'selected' : '' ?>>
                            <?= $type ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre del Asegurado <span class="required">*</span></label>
                <input type="text" name="insured_name" class="form-input" 
                       value="<?= htmlspecialchars($policy['insured_name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="insured_email" class="form-input" 
                       value="<?= htmlspecialchars($policy['insured_email']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono <span class="required">*</span></label>
                <input type="tel" name="insured_phone" class="form-input" 
                       value="<?= htmlspecialchars($policy['insured_phone']) ?>" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Monto de Cobertura <span class="required">*</span></label>
                <input type="number" name="coverage_amount" class="form-input" step="0.01" min="0"
                       value="<?= $policy['coverage_amount'] ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Monto de Prima <span class="required">*</span></label>
                <input type="number" name="premium_amount" class="form-input" step="0.01" min="0"
                       value="<?= $policy['premium_amount'] ?>" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Fecha de Inicio <span class="required">*</span></label>
                <input type="date" name="start_date" class="form-input" 
                       value="<?= $policy['start_date'] ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fecha de Fin <span class="required">*</span></label>
                <input type="date" name="end_date" class="form-input" 
                       value="<?= $policy['end_date'] ?>" required>
            </div>
        </div>

        <div class="form-group full-width">
            <label class="form-label">Descripción Adicional</label>
            <textarea name="description" class="form-textarea"><?= htmlspecialchars($policy['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <a href="<?= url('policies/view/' . $id) ?>" class="btn btn-secondary">← Cancelar</a>
            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
