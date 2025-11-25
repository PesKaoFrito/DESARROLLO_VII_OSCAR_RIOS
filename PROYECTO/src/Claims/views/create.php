<?php
/**
 * Claims - Create View
 * URL: /claims/create
 */

$policies = $policyManager->getActivePolicies();
$categories = $categoryManager->getAllCategories();
$statuses = $statusManager->getAllStatuses();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insuredName = sanitize($_POST['insured_name']);
    $category = sanitize($_POST['category']);
    $amount = sanitize($_POST['amount']);
    $status = sanitize($_POST['status']);
    $policyId = $_POST['policy_id'] ?? null;

    // Validaciones
    if (!validateRequired($insuredName)) $errors[] = 'El nombre del asegurado es requerido';
    if (!validateRequired($category)) $errors[] = 'La categoría es requerida';
    if (!validateNumeric($amount) || $amount <= 0) $errors[] = 'El monto debe ser un número positivo';
    if (!validateRequired($status)) $errors[] = 'El estado es requerido';

    if (empty($errors)) {
        $claimNumber = generateClaimNumber();
        
        $claimData = [
            'claim_number' => $claimNumber,
            'policy_id' => $policyId,
            'insured_name' => $insuredName,
            'category' => $category,
            'amount' => $amount,
            'status' => $status,
            'analyst_id' => getCurrentUser()['id'],
            'supervisor_id' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $claim = new Claim($claimData);
        
        $claimId = $claimManager->createClaim($claim);
        if ($claimId) {
            setFlashMessage('success', 'Reclamo creado exitosamente');
            redirectTo(url('claims/view/' . $claimId));
        } else {
            $errors[] = 'Error al crear el reclamo';
        }
    }
}

$pageTitle = 'Nuevo Reclamo';
$showNav = true;

ob_start();
?>

<style>
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 800px;
    }
    .form-header {
        margin-bottom: 2rem;
    }
    .form-header h1 {
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .alert {
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .required {
        color: #dc3545;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1>➕ Nuevo Reclamo</h1>
        <p>Complete el formulario para registrar un nuevo reclamo</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('claims/create') ?>">
        <div class="form-group">
            <label for="policy_id">Póliza (opcional)</label>
            <select id="policy_id" name="policy_id">
                <option value="">Sin póliza asociada</option>
                <?php foreach ($policies as $policy): ?>
                    <option value="<?= $policy['id'] ?>">
                        <?= htmlspecialchars($policy['policy_number']) ?> - <?= htmlspecialchars($policy['insured_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="insured_name">Nombre del Asegurado <span class="required">*</span></label>
            <input type="text" id="insured_name" name="insured_name" required value="<?= $_POST['insured_name'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="category">Categoría <span class="required">*</span></label>
            <select id="category" name="category" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['name'] ?>" <?= (($_POST['category'] ?? '') === $cat['name']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Monto Reclamado <span class="required">*</span></label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" required value="<?= $_POST['amount'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="status">Estado Inicial <span class="required">*</span></label>
            <select id="status" name="status" required>
                <?php foreach ($statuses as $stat): ?>
                    <option value="<?= $stat['name'] ?>" <?= (($_POST['status'] ?? 'pending') === $stat['name']) ? 'selected' : '' ?>>
                        <?= ucfirst($stat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar Reclamo</button>
            <a href="<?= url('claims') ?>" class="btn btn-secondary">❌ Cancelar</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
