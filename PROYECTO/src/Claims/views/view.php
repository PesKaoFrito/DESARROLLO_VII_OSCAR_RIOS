<?php
<<<<<<< HEAD
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
=======
/**
 * Claims - View Detail
 * URL: /claims/view/123
 */

$claim = $claimManager->getClaimById($id);

if (!$claim) {
    setFlashMessage('error', 'Reclamo no encontrado');
    redirectTo(url('claims'));
}

$pageTitle = 'Detalle del Reclamo';
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
$showNav = true;

ob_start();
?>

<<<<<<< HEAD
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

=======
<style>
    .detail-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 900px;
    }
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }
    .detail-header h1 {
        color: #333;
        margin: 0;
    }
    .claim-number {
        font-size: 0.9rem;
        color: #666;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .detail-field {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .detail-field label {
        display: block;
        font-weight: 600;
        color: #666;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }
    .detail-field .value {
        color: #333;
        font-size: 1.1rem;
    }
    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    .badge-approved {
        background: #d4edda;
        color: #155724;
    }
    .badge-rejected {
        background: #f8d7da;
        color: #721c24;
    }
    .badge-in-review {
        background: #d1ecf1;
        color: #0c5460;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>

<div class="detail-container">
    <div class="detail-header">
        <div>
            <h1>📋 Detalle del Reclamo</h1>
            <div class="claim-number"><?= htmlspecialchars($claim['claim_number']) ?></div>
        </div>
        <span class="badge badge-<?= $claim['status'] ?>">
            <?= ucfirst($claim['status']) ?>
        </span>
    </div>

    <div class="detail-grid">
        <div class="detail-field">
            <label>Asegurado</label>
            <div class="value"><?= htmlspecialchars($claim['insured_name']) ?></div>
        </div>

        <div class="detail-field">
            <label>Categoría</label>
            <div class="value"><?= htmlspecialchars($claim['category']) ?></div>
        </div>

        <div class="detail-field">
            <label>Monto Reclamado</label>
            <div class="value"><?= formatMoney($claim['amount']) ?></div>
        </div>

        <div class="detail-field">
            <label>Analista</label>
            <div class="value">ID: <?= $claim['analyst_id'] ?></div>
        </div>

        <?php if ($claim['policy_id']): ?>
        <div class="detail-field">
            <label>Póliza Asociada</label>
            <div class="value">ID: <?= $claim['policy_id'] ?></div>
        </div>
        <?php endif; ?>

        <div class="detail-field">
            <label>Fecha de Creación</label>
            <div class="value"><?= formatDate($claim['created_at']) ?></div>
        </div>

        <?php if ($claim['updated_at'] !== $claim['created_at']): ?>
        <div class="detail-field">
            <label>Última Actualización</label>
            <div class="value"><?= formatDate($claim['updated_at']) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="action-buttons">
        <a href="<?= url('claims/edit/' . $claim['id']) ?>" class="btn btn-primary">✏️ Editar</a>
        <a href="<?= url('claims') ?>" class="btn btn-secondary">← Volver al Listado</a>
    </div>
</div>

>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
