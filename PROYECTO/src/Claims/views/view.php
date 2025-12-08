<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../ClaimManager.php';

requireAuth();

$currentUser = getCurrentUser();
$claimId = $_GET['id'] ?? 0;
$claimManager = new ClaimManager();
$claim = $claimManager->getClaimById($claimId);

if (!$claim) {
    $_SESSION['error_message'] = 'Reclamo no encontrado';
    redirectTo('src/Claims/');
}

// Verificar si el usuario puede ver este reclamo
$canView = false;
if ($currentUser['role'] === 'admin') {
    $canView = true;
} elseif ($currentUser['role'] === 'supervisor' && $claim['supervisor_id'] == $currentUser['id']) {
    $canView = true;
} elseif ($currentUser['role'] === 'analyst' && $claim['analyst_id'] == $currentUser['id']) {
    $canView = true;
}

if (!$canView) {
    $_SESSION['error_message'] = 'No tienes permisos para ver este reclamo';
    redirectTo('src/Claims/');
}

$canEdit = $claimManager->canUserEditClaim($claimId, $currentUser['id'], $currentUser['role']);

$pageTitle = 'Detalle del Reclamo #' . $claim['claim_number'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-alt"></i> Reclamo #<?= htmlspecialchars($claim['claim_number']) ?></h1>
        <p class="subtitle">Detalles completos del reclamo</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <?php if ($canEdit): ?>
        <a href="<?= url('claims/edit?id=' . $claim['id']) ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <?php endif; ?>
        <a href="<?= url('claims') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= $_SESSION['success_message'] ?>
</div>
<?php unset($_SESSION['success_message']); endif; ?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Información General</h3>
        <span class="badge badge-<?= $claim['status_name'] ?>" style="font-size: 1rem;">
            <?= ucfirst($claim['status_name']) ?>
        </span>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-hashtag"></i> Número de Reclamo</label>
                <value><strong><?= htmlspecialchars($claim['claim_number']) ?></strong></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar"></i> Fecha de Creación</label>
                <value><?= formatDate($claim['created_at']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-tags"></i> Categoría</label>
                <value><span class="badge badge-secondary"><?= htmlspecialchars($claim['category_name']) ?></span></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-dollar-sign"></i> Monto Reclamado</label>
                <value><strong style="font-size: 1.5rem; color: var(--primary-color);"><?= formatMoney($claim['amount']) ?></strong></value>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-file-contract"></i> Información de la Póliza</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-file-signature"></i> Número de Póliza</label>
                <value><?= htmlspecialchars($claim['policy_number'] ?? 'N/A') ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-shield-alt"></i> Tipo de Póliza</label>
                <value><span class="badge badge-info"><?= ucfirst($claim['policy_type'] ?? 'N/A') ?></span></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-user"></i> Titular de la Póliza</label>
                <value><?= htmlspecialchars($claim['policy_holder'] ?? 'N/A') ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-money-bill-wave"></i> Cobertura</label>
                <value><?= formatMoney($claim['coverage_amount'] ?? 0) ?></value>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-circle"></i> Información del Asegurado</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-user"></i> Nombre Completo</label>
                <value><?= htmlspecialchars($claim['insured_name']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-phone"></i> Teléfono</label>
                <value><?= htmlspecialchars($claim['insured_phone'] ?? 'No especificado') ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <value><?= htmlspecialchars($claim['insured_email'] ?? 'No especificado') ?></value>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-file-alt"></i> Descripción del Reclamo</h3>
    </div>
    <div class="card-body">
        <div class="description-box">
            <?= nl2br(htmlspecialchars($claim['description'])) ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-tie"></i> Asignación y Seguimiento</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-hashtag"></i> ID Analista</label>
                <value><?= $claim['analyst_id'] ?? 'Sin asignar' ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-user-cog"></i> Nombre del Analista</label>
                <value><?= htmlspecialchars($claim['analyst_name'] ?? 'Sin asignar') ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-clock"></i> Última Actualización</label>
                <value><?= formatDate($claim['updated_at'] ?? $claim['created_at']) ?></value>
            </div>
        </div>
    </div>
</div>

<style>
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item label {
    font-weight: 600;
    color: #666;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.detail-item value {
    font-size: 1.1rem;
    color: #333;
}

.description-box {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color);
    line-height: 1.8;
    font-size: 1.05rem;
}
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
