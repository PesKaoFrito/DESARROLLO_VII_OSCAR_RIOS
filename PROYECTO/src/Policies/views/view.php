<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/helpers.php';
require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../PolicyManager.php';

requireAuth();

$policyManager = new PolicyManager();
$policyId = $_GET['id'] ?? null;

if (!$policyId) {
    header('Location: ' . url('policies'));
    exit;
}

$policy = $policyManager->getPolicyById($policyId);

if (!$policy) {
    header('Location: ' . url('policies'));
    exit;
}

$pageTitle = 'Detalle de Póliza - ' . $policy['policy_number'];
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-contract"></i> Detalle de Póliza</h1>
        <p class="subtitle">Información completa de la póliza #<?= htmlspecialchars($policy['policy_number']) ?></p>
    </div>
    <div class="btn-group">
        <a href="<?= url('policies/edit?id=' . $policy['id']) ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
        <a href="<?= url('policies') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Información General</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Número de Póliza:</label>
                        <strong><?= htmlspecialchars($policy['policy_number']) ?></strong>
                    </div>
                    <div class="info-item">
                        <label>Estado:</label>
                        <span class="badge badge-<?= $policy['status'] ?>">
                            <?= translateStatus($policy['status']) ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Tipo de Póliza:</label>
                        <span><?= ucfirst($policy['policy_type']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Monto de Cobertura:</label>
                        <strong><?= formatMoney($policy['coverage_amount']) ?></strong>
                    </div>
                    <div class="info-item">
                        <label>Prima:</label>
                        <strong><?= formatMoney($policy['premium_amount']) ?></strong>
                    </div>
                    <div class="info-item">
                        <label>Fecha de Inicio:</label>
                        <span><?= formatDate($policy['start_date']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Fecha de Vencimiento:</label>
                        <span><?= formatDate($policy['end_date']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Datos del Asegurado</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nombre Completo:</label>
                        <strong><?= htmlspecialchars($policy['insured_name']) ?></strong>
                    </div>
                    <div class="info-item">
                        <label>Correo Electrónico:</label>
                        <span><?= htmlspecialchars($policy['insured_email']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Teléfono:</label>
                        <span><?= htmlspecialchars($policy['insured_phone']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Dirección:</label>
                        <span><?= htmlspecialchars($policy['insured_address'] ?? 'No especificada') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 1rem;">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Registro</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Fecha de Creación:</label>
                        <span><?= formatDate($policy['created_at']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Última Actualización:</label>
                        <span><?= formatDate($policy['updated_at']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-size: 0.875rem;
    color: #666;
    font-weight: 500;
}

.info-item strong,
.info-item span {
    font-size: 1rem;
}

.row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

@media (min-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layout.php';
?>
