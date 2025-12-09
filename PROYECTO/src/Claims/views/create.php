<?php
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

$errors = [];
$success = false;

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
            redirectTo('claims/view?id=' . $claimId);
        } else {
            $errors[] = 'Error al crear el reclamo';
        }
    }
}

$pageTitle = 'Nuevo Reclamo - Sistema de Gestión';
$showNav = true;

ob_start();
?>

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

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
