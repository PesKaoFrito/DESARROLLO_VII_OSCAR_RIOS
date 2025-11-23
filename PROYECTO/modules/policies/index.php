<?php
require_once '../../config.php';
require_once '../../includes/auth.php';
require_once '../../includes/helpers.php';
require_once '../../src/Database.php';
require_once '../../src/Policies/PolicyManager.php';

requireAuth();

$policyManager = new PolicyManager();
$searchTerm = $_GET['search'] ?? '';

if ($searchTerm) {
    $policies = $policyManager->searchPolicies($searchTerm);
} else {
    $policies = $policyManager->getAllPolicies();
}

$pageTitle = 'Pólizas';
$showNav = true;

ob_start();
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .search-section {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .search-form {
        display: flex;
        gap: 1rem;
    }
    .search-form input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .policies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .policy-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
    }
    .policy-card.expired {
        border-left-color: #dc3545;
        opacity: 0.7;
    }
    .policy-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .policy-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
    }
    .policy-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
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
    .policy-info {
        margin-bottom: 1rem;
    }
    .policy-info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .policy-info-label {
        font-weight: 600;
        color: #666;
    }
    .policy-info-value {
        color: #333;
    }
</style>

<div class="page-header">
    <h1>📄 Gestión de Pólizas</h1>
    <a href="create.php" class="btn btn-primary">➕ Nueva Póliza</a>
</div>

<div class="search-section">
    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Buscar por número, asegurado, email..." value="<?= htmlspecialchars($searchTerm) ?>">
        <button type="submit" class="btn btn-primary">🔍 Buscar</button>
        <?php if ($searchTerm): ?>
            <a href="index.php" class="btn btn-secondary">🔄 Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($policies)): ?>
    <div style="text-align: center; padding: 3rem; background: white; border-radius: 10px;">
        <p style="font-size: 3rem;">📄</p>
        <p>No se encontraron pólizas</p>
        <a href="create.php" class="btn btn-primary" style="margin-top: 1rem;">Crear la primera</a>
    </div>
<?php else: ?>
    <div class="policies-grid">
        <?php foreach ($policies as $policy): ?>
            <?php
            $isExpired = strtotime($policy['end_date']) < time();
            $statusClass = $isExpired ? 'expired' : 'active';
            ?>
            <div class="policy-card <?= $statusClass ?>">
                <div class="policy-header">
                    <div class="policy-number"><?= htmlspecialchars($policy['policy_number']) ?></div>
                    <span class="policy-status <?= $statusClass ?>"><?= $isExpired ? 'Vencida' : 'Activa' ?></span>
                </div>
                
                <div class="policy-info">
                    <div class="policy-info-row">
                        <span class="policy-info-label">Asegurado:</span>
                        <span class="policy-info-value"><?= htmlspecialchars($policy['insured_name']) ?></span>
                    </div>
                    <div class="policy-info-row">
                        <span class="policy-info-label">Tipo:</span>
                        <span class="policy-info-value"><?= htmlspecialchars($policy['policy_type']) ?></span>
                    </div>
                    <div class="policy-info-row">
                        <span class="policy-info-label">Cobertura:</span>
                        <span class="policy-info-value"><?= formatMoney($policy['coverage_amount']) ?></span>
                    </div>
                    <div class="policy-info-row">
                        <span class="policy-info-label">Prima:</span>
                        <span class="policy-info-value"><?= formatMoney($policy['premium_amount']) ?></span>
                    </div>
                    <div class="policy-info-row">
                        <span class="policy-info-label">Vigencia:</span>
                        <span class="policy-info-value">
                            <?= formatDate($policy['start_date']) ?> - <?= formatDate($policy['end_date']) ?>
                        </span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                    <a href="view.php?id=<?= $policy['id'] ?>" class="btn btn-sm btn-primary">👁️ Ver</a>
                    <a href="edit.php?id=<?= $policy['id'] ?>" class="btn btn-sm btn-secondary">✏️ Editar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require '../../views/layout.php';
?>
