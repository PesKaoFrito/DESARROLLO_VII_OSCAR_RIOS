<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../ClaimManager.php';
require_once __DIR__ . '/../../Policies/PolicyManager.php';
require_once __DIR__ . '/../../Categories/CategoryManager.php';
require_once __DIR__ . '/../../Statuses/StatusManager.php';
require_once __DIR__ . '/../../Users/UserManager.php';

requireAuth();

$currentUser = getCurrentUser();
$claimId = $_GET['id'] ?? 0;
$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$categoryManager = new CategoryManager();
$statusManager = new StatusManager();
$userManager = new UserManager();

$claim = $claimManager->getClaimById($claimId);

if (!$claim) {
    $_SESSION['error_message'] = 'Reclamo no encontrado';
    redirectTo('src/Claims/');
}

// Verificar permisos de edición
if (!$claimManager->canUserEditClaim($claimId, $currentUser['id'], $currentUser['role'])) {
    $_SESSION['error_message'] = 'No tienes permisos para editar este reclamo';
    redirectTo('src/Claims/');
}

$policies = $policyManager->getAllPolicies();
$categories = $categoryManager->getAllCategories();
$statuses = $statusManager->getAllStatuses();
$analysts = $userManager->getUsersByRole('analyst');

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'policy_id' => (int)$_POST['policy_id'],
        'category_id' => (int)$_POST['category_id'],
        'status_id' => (int)$_POST['status_id'],
        'insured_name' => sanitize($_POST['insured_name']),
        'insured_phone' => sanitize($_POST['insured_phone']),
        'insured_email' => sanitize($_POST['insured_email']),
        'amount' => (float)$_POST['amount'],
        'description' => sanitize($_POST['description']),
        'analyst_id' => (int)$_POST['analyst_id']
    ];
    
    $result = $claimManager->updateClaim($claimId, $data);
    
    if ($result) {
        $_SESSION['success_message'] = 'Reclamo actualizado exitosamente';
        redirectTo('src/Claims/views/view.php?id=' . $claimId);
    } else {
        $errors[] = 'Error al actualizar el reclamo';
    }
}

$pageTitle = 'Editar Reclamo #' . $claim['claim_number'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-edit"></i> Editar Reclamo #<?= htmlspecialchars($claim['claim_number']) ?></h1>
        <p class="subtitle">Modifica la información del reclamo</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= url('claims/view?id=' . $claim['id']) ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Ver Detalle
        </a>
        <a href="<?= url('claims') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Errores encontrados:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <?php if (is_array($error)): ?>
                    <?php foreach ($error as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<form method="POST" action="" class="form-container">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-file-contract"></i> Información de la Póliza</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="policy_id">Póliza <span class="required">*</span></label>
                    <select id="policy_id" name="policy_id" required class="form-control">
                        <option value="">Seleccione una póliza</option>
                        <?php foreach ($policies as $policy): ?>
                            <option value="<?= $policy['id'] ?>" <?= $claim['policy_id'] == $policy['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($policy['policy_number']) ?> - <?= htmlspecialchars($policy['insured_name']) ?> (<?= ucfirst($policy['policy_type']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Categoría <span class="required">*</span></label>
                    <select id="category_id" name="category_id" required class="form-control">
                        <option value="">Seleccione una categoría</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $claim['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="status_id">Estado <span class="required">*</span></label>
                <select id="status_id" name="status_id" required class="form-control">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= $claim['status_id'] == $status['id'] ? 'selected' : '' ?>>
                            <?= translateStatus($status['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Información del Asegurado</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="insured_name">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="insured_name" name="insured_name" required
                           value="<?= htmlspecialchars($claim['insured_name']) ?>"
                           placeholder="Ej: Juan Pérez"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="insured_phone">Teléfono</label>
                    <input type="tel" id="insured_phone" name="insured_phone"
                           value="<?= htmlspecialchars($claim['insured_phone'] ?? '') ?>"
                           placeholder="+507 6000-0000"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="insured_email">Correo Electrónico</label>
                <input type="email" id="insured_email" name="insured_email"
                       value="<?= htmlspecialchars($claim['insured_email'] ?? '') ?>"
                       placeholder="ejemplo@correo.com"
                       class="form-control">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-dollar-sign"></i> Detalles del Reclamo</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="amount">Monto Reclamado <span class="required">*</span></label>
                <input type="number" id="amount" name="amount" required min="0.01" step="0.01"
                       value="<?= htmlspecialchars($claim['amount']) ?>"
                       placeholder="0.00"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Descripción del Reclamo <span class="required">*</span></label>
                <textarea id="description" name="description" required rows="6"
                          placeholder="Describa detalladamente el incidente..."
                          class="form-control"><?= htmlspecialchars($claim['description']) ?></textarea>
            </div>
        </div>
    </div>

    <?php if (hasRole('admin') || hasRole('supervisor')): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-users-cog"></i> Asignación</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="analyst_id">Analista Asignado <span class="required">*</span></label>
                <select id="analyst_id" name="analyst_id" required class="form-control">
                    <?php foreach ($analysts as $analyst): ?>
                        <option value="<?= $analyst['id'] ?>" <?= $claim['analyst_id'] == $analyst['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($analyst['name']) ?> - <?= htmlspecialchars($analyst['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Actualizar Reclamo
        </button>
        <a href="<?= url('claims/view?id=' . $claim['id']) ?>" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
        </a>
    </div>
</form>

<script>
// Datos de pólizas y categorías con sus tipos
const policies = <?= json_encode($policies) ?>;
const categories = <?= json_encode($categories) ?>;

// Referencias a los elementos
const policySelect = document.getElementById('policy_id');
const categorySelect = document.getElementById('category_id');

// Guardar la categoría actual del reclamo
const currentCategoryId = <?= $claim['category_id'] ?>;

// Función para filtrar categorías según el tipo de póliza
function filterCategories() {
    const selectedPolicyId = policySelect.value;
    
    if (!selectedPolicyId) {
        categorySelect.disabled = true;
        categorySelect.innerHTML = '<option value="">Primero seleccione una póliza</option>';
        return;
    }
    
    // Buscar la póliza seleccionada
    const selectedPolicy = policies.find(p => p.id == selectedPolicyId);
    
    if (!selectedPolicy) {
        return;
    }
    
    const policyType = selectedPolicy.policy_type;
    
    // Filtrar categorías que coincidan con el tipo de póliza
    const filteredCategories = categories.filter(c => c.policy_type === policyType);
    
    // Actualizar el select de categorías
    categorySelect.disabled = false;
    categorySelect.innerHTML = '<option value="">Seleccione una categoría</option>';
    
    filteredCategories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        
        // Mantener seleccionada la categoría actual
        if (category.id == currentCategoryId) {
            option.selected = true;
        }
        
        categorySelect.appendChild(option);
    });
    
    // Mensaje si no hay categorías disponibles
    if (filteredCategories.length === 0) {
        categorySelect.innerHTML = '<option value="">No hay categorías para este tipo de póliza</option>';
        categorySelect.disabled = true;
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Agregar evento change a la póliza
    policySelect.addEventListener('change', filterCategories);
    
    // Filtrar categorías según la póliza actual
    filterCategories();
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
