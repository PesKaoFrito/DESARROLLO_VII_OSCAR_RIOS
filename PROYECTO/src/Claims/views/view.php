<?php
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
$showNav = true;

ob_start();
?>

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

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
