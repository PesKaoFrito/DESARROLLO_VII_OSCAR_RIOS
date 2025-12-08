<?php
<<<<<<< HEAD
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
=======
/**
 * Policies - View Detail
 * URL: /policies/view/123
 */

$policy = $policyManager->getPolicyById($id);

if (!$policy) {
    setFlashMessage('error', 'Póliza no encontrada');
    redirectTo(url('policies'));
}

$isExpired = strtotime($policy['end_date']) < time();
$pageTitle = 'Detalles de Póliza';
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
$showNav = true;

ob_start();
?>

<<<<<<< HEAD
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

=======
<style>
    .policy-details {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 0 auto;
    }
    .policy-header {
        border-bottom: 3px solid #667eea;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    .policy-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .policy-number {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
    }
    .policy-status {
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
    }
    .policy-status.active {
        background: #d4edda;
        color: #155724;
    }
    .policy-status.expired {
        background: #f8d7da;
        color: #721c24;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .info-section {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .info-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 1rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #666;
    }
    .info-value {
        color: #333;
        text-align: right;
    }
    .actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>

<div class="policy-details">
    <div class="policy-header">
        <div class="policy-title">
            <h1 class="policy-number"><?= htmlspecialchars($policy['policy_number']) ?></h1>
            <span class="policy-status <?= $isExpired ? 'expired' : 'active' ?>">
                <?= $isExpired ? '❌ Vencida' : '✅ Activa' ?>
            </span>
        </div>
        <p style="color: #666; margin: 0;"><?= htmlspecialchars($policy['policy_type']) ?></p>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <div class="info-section-title">👤 Información del Asegurado</div>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value"><?= htmlspecialchars($policy['insured_name']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?= htmlspecialchars($policy['insured_email']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value"><?= htmlspecialchars($policy['insured_phone']) ?></span>
            </div>
            <?php if (!empty($policy['insured_address'])): ?>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span class="info-value"><?= htmlspecialchars($policy['insured_address']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="info-section">
            <div class="info-section-title">💰 Información Financiera</div>
            <div class="info-row">
                <span class="info-label">Cobertura:</span>
                <span class="info-value"><?= formatMoney($policy['coverage_amount']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Prima:</span>
                <span class="info-value"><?= formatMoney($policy['premium_amount']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Inicio:</span>
                <span class="info-value"><?= formatDate($policy['start_date']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Fin:</span>
                <span class="info-value"><?= formatDate($policy['end_date']) ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($policy['description'])): ?>
    <div class="info-section">
        <div class="info-section-title">📝 Descripción</div>
        <p style="margin: 0; color: #333;"><?= nl2br(htmlspecialchars($policy['description'])) ?></p>
    </div>
    <?php endif; ?>

    <div class="actions">
        <a href="<?= url('policies') ?>" class="btn btn-secondary">← Volver</a>
        <a href="<?= url('policies/edit/' . $policy['id']) ?>" class="btn btn-primary">✏️ Editar</a>
    </div>
</div>

>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
