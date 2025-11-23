<?php
require_once '../../config.php';
require_once '../../includes/auth.php';
require_once '../../includes/helpers.php';
require_once '../../src/Database.php';
require_once '../../src/Policies/PolicyManager.php';

requireAuth();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$policyManager = new PolicyManager();
$policy = $policyManager->getPolicyById($id);

if (!$policy) {
    $_SESSION['error'] = 'Póliza no encontrada';
    header('Location: index.php');
    exit;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
        font-weight: 500;
        text-align: right;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e0e0e0;
    }
</style>

<div class="policy-details">
    <div class="policy-header">
        <div class="policy-title">
            <div>
                <div class="policy-number"><?= htmlspecialchars($policy['policy_number']) ?></div>
                <div style="color: #666; margin-top: 0.5rem;">
                    Creada: <?= formatDate($policy['created_at']) ?>
                </div>
            </div>
            <span class="policy-status <?= $isExpired ? 'expired' : 'active' ?>">
                <?= $isExpired ? '❌ Vencida' : '✅ Activa' ?>
            </span>
        </div>
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
        </div>

        <div class="info-section">
            <div class="info-section-title">📋 Detalles de la Póliza</div>
            <div class="info-row">
                <span class="info-label">Tipo:</span>
                <span class="info-value"><?= htmlspecialchars($policy['policy_type']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Cobertura:</span>
                <span class="info-value"><?= formatMoney($policy['coverage_amount']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Prima:</span>
                <span class="info-value"><?= formatMoney($policy['premium_amount']) ?></span>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-section-title">📅 Vigencia</div>
        <div class="info-row">
            <span class="info-label">Fecha de Inicio:</span>
            <span class="info-value"><?= formatDate($policy['start_date']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Fin:</span>
            <span class="info-value"><?= formatDate($policy['end_date']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Duración:</span>
            <span class="info-value">
                <?php
                $start = new DateTime($policy['start_date']);
                $end = new DateTime($policy['end_date']);
                $interval = $start->diff($end);
                echo $interval->days . ' días';
                ?>
            </span>
        </div>
    </div>

    <?php if (!empty($policy['description'])): ?>
    <div class="info-section">
        <div class="info-section-title">📝 Descripción</div>
        <div style="padding: 1rem 0; line-height: 1.6;">
            <?= nl2br(htmlspecialchars($policy['description'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="action-buttons">
        <a href="index.php" class="btn btn-secondary">← Volver al Listado</a>
        <a href="edit.php?id=<?= $policy['id'] ?>" class="btn btn-primary">✏️ Editar Póliza</a>
        <?php if (hasRole('admin')): ?>
        <button onclick="if(confirm('¿Está seguro de eliminar esta póliza?')) { window.location.href='delete.php?id=<?= $policy['id'] ?>'; }" class="btn btn-danger">
            🗑️ Eliminar
        </button>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require '../../views/layout.php';
?>
