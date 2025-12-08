<?php
<<<<<<< HEAD
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../ClaimManager.php';
require_once __DIR__ . '/../../Policies/PolicyManager.php';
require_once __DIR__ . '/../../Categories/CategoryManager.php';
require_once __DIR__ . '/../../Users/UserManager.php';

requireAuth();

$claimManager = new ClaimManager();
$policyManager = new PolicyManager();
$categoryManager = new CategoryManager();
$userManager = new UserManager();

// Obtener datos para los selects
$policies = $policyManager->getAllPolicies();
$categories = $categoryManager->getAllCategories();
$analysts = $userManager->getUsersByRole('analyst');
=======
/**
 * Claims - Create View
 * URL: /claims/create
 */

$policies = $policyManager->getActivePolicies();
$categories = $categoryManager->getAllCategories();
$statuses = $statusManager->getAllStatuses();
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15

$errors = [];
$success = false;

<<<<<<< HEAD
// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validaciones
    if (empty($_POST['policy_id'])) {
        $errors[] = 'Debes seleccionar una póliza';
    }
    if (empty($_POST['category_id'])) {
        $errors[] = 'Debes seleccionar una categoría';
    }
    if (empty($_POST['insured_name'])) {
        $errors[] = 'El nombre del asegurado es requerido';
    }
    if (empty($_POST['amount']) || $_POST['amount'] <= 0) {
        $errors[] = 'El monto debe ser mayor a 0';
    }
    if (empty($_POST['description'])) {
        $errors[] = 'La descripción es requerida';
    }
    
    if (empty($errors)) {
        $currentUser = getCurrentUser();
        
        // Determinar el analyst_id según el rol
        $analystId = null;
        if (!empty($_POST['analyst_id'])) {
            // Si se seleccionó un analista específicamente
            $analystId = (int)$_POST['analyst_id'];
        } elseif ($currentUser['role'] === 'analyst') {
            // Si quien crea es analista, se auto-asigna
            $analystId = $currentUser['id'];
        }
        // Si es admin/supervisor y no seleccionó analista, queda null
        
        $data = [
            'policy_id' => (int)$_POST['policy_id'],
            'category_id' => (int)$_POST['category_id'],
            'insured_name' => sanitize($_POST['insured_name']),
            'insured_phone' => sanitize($_POST['insured_phone']),
            'insured_email' => sanitize($_POST['insured_email']),
            'amount' => (float)$_POST['amount'],
            'description' => sanitize($_POST['description']),
            'analyst_id' => $analystId
        ];
        
        $claimId = $claimManager->createClaim($data);
        
        if ($claimId) {
            $success = true;
            $_SESSION['success_message'] = 'Reclamo creado exitosamente';
            redirectTo('src/Claims/views/view.php?id=' . $claimId);
=======
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
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
        } else {
            $errors[] = 'Error al crear el reclamo';
        }
    }
}

<<<<<<< HEAD
$pageTitle = 'Nuevo Reclamo - Sistema de Gestión';
=======
$pageTitle = 'Nuevo Reclamo';
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
$showNav = true;

ob_start();
?>

<<<<<<< HEAD
<div class="page-header">
    <div>
        <h1><i class="fas fa-file-alt"></i> Crear Nuevo Reclamo</h1>
        <p class="subtitle">Complete el formulario para registrar un nuevo reclamo</p>
    </div>
    <a href="<?= url('claims') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Errores encontrados:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<form method="POST" action="" class="form-container" style="margin-top: 2rem;">
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
                            <option value="<?= $policy['id'] ?>" <?= (isset($_POST['policy_id']) && $_POST['policy_id'] == $policy['id']) ? 'selected' : '' ?>>
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
                            <option value="<?= $category['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                           value="<?= htmlspecialchars($_POST['insured_name'] ?? '') ?>"
                           placeholder="Ej: Juan Pérez"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="insured_phone">Teléfono</label>
                    <input type="tel" id="insured_phone" name="insured_phone"
                           value="<?= htmlspecialchars($_POST['insured_phone'] ?? '') ?>"
                           placeholder="+507 6000-0000"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="insured_email">Correo Electrónico</label>
                <input type="email" id="insured_email" name="insured_email"
                       value="<?= htmlspecialchars($_POST['insured_email'] ?? '') ?>"
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
                       value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>"
                       placeholder="0.00"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Descripción del Reclamo <span class="required">*</span></label>
                <textarea id="description" name="description" required rows="6"
                          placeholder="Describa detalladamente el incidente o situación que motiva el reclamo..."
                          class="form-control"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
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
                <label for="analyst_id">Analista Asignado</label>
                <select id="analyst_id" name="analyst_id" class="form-control">
                    <option value="">Asignar automáticamente</option>
                    <?php foreach ($analysts as $analyst): ?>
                        <option value="<?= $analyst['id'] ?>" <?= (isset($_POST['analyst_id']) && $_POST['analyst_id'] == $analyst['id']) ? 'selected' : '' ?>>
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
            <i class="fas fa-save"></i> Crear Reclamo
        </button>
        <a href="<?= url('claims') ?>" class="btn btn-secondary">
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

// Función para filtrar categorías según el tipo de póliza
function filterCategories() {
    const selectedPolicyId = policySelect.value;
    
    if (!selectedPolicyId) {
        // Si no hay póliza seleccionada, deshabilitar categorías
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
    // Deshabilitar categoría inicialmente
    categorySelect.disabled = true;
    categorySelect.innerHTML = '<option value="">Primero seleccione una póliza</option>';
    
    // Agregar evento change a la póliza
    policySelect.addEventListener('change', filterCategories);
    
    // Si ya hay una póliza seleccionada (en caso de error de validación), filtrar
    if (policySelect.value) {
        filterCategories();
        
        // Restaurar la categoría previamente seleccionada
        const previousCategory = '<?= $_POST['category_id'] ?? '' ?>';
        if (previousCategory) {
            categorySelect.value = previousCategory;
        }
    }
});
</script>
=======
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
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
