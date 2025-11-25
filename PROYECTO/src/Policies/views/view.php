<?php
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
$showNav = true;

ob_start();
?>

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

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
