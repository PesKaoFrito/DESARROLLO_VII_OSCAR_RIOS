<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../PolicyManager.php';

requireAuth();

$policyId = $_GET['id'] ?? 0;
$policyManager = new PolicyManager();
$policy = $policyManager->getPolicyById($policyId);

if (!$policy) {
    $_SESSION['error_message'] = 'Póliza no encontrada';
    redirectTo('src/Policies/');
}

$pageTitle = 'Detalle de la Póliza #' . $policy['policy_number'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-contract"></i> Póliza #<?= htmlspecialchars($policy['policy_number']) ?></h1>
        <p class="subtitle">Detalles completos de la póliza</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= url('policies/edit?id=' . $policy['id']) ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="<?= url('policies') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?= $_SESSION['success_message'] ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Información General -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Información General</h3>
        <span class="badge badge-<?= $policy['status'] ?>" style="font-size: 1rem;">
            <?= ucfirst($policy['status']) ?>
        </span>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-hashtag"></i> Número de Póliza</label>
                <value><strong><?= htmlspecialchars($policy['policy_number']) ?></strong></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-shield-alt"></i> Tipo de Póliza</label>
                <value><span class="badge badge-info"><?= ucfirst($policy['policy_type']) ?></span></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar-check"></i> Fecha de Inicio</label>
                <value><?= formatDate($policy['start_date']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar-times"></i> Fecha de Vencimiento</label>
                <value><?= formatDate($policy['end_date']) ?></value>
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
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-user-circle"></i> Nombre Completo</label>
                <value><?= htmlspecialchars($policy['insured_name']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-envelope"></i> Email</label>
                <value>
                    <?php if ($policy['insured_email']): ?>
                        <a href="mailto:<?= htmlspecialchars($policy['insured_email']) ?>">
                            <?= htmlspecialchars($policy['insured_email']) ?>
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-phone"></i> Teléfono</label>
                <value>
                    <?php if ($policy['insured_phone']): ?>
                        <a href="tel:<?= htmlspecialchars($policy['insured_phone']) ?>">
                            <?= htmlspecialchars($policy['insured_phone']) ?>
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                <value><?= htmlspecialchars($policy['insured_address'] ?? 'N/A') ?></value>
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
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-hand-holding-usd"></i> Monto de Cobertura</label>
                <value><strong style="font-size: 1.5rem; color: var(--primary-color);"><?= formatMoney($policy['coverage_amount']) ?></strong></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-receipt"></i> Prima Mensual</label>
                <value><strong style="font-size: 1.2rem; color: var(--success-color);"><?= formatMoney($policy['premium_amount']) ?></strong></value>
            </div>
        </div>
    </div>
</div>

<!-- Fechas de Registro -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> Registro</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class="fas fa-calendar-plus"></i> Creada</label>
                <value><?= formatDate($policy['created_at']) ?></value>
            </div>
            <div class="detail-item">
                <label><i class="fas fa-calendar-edit"></i> Última Actualización</label>
                <value><?= formatDate($policy['updated_at']) ?></value>
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
    color: var(--light-text);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.detail-item value {
    font-size: 1rem;
    color: var(--dark-text);
}
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
