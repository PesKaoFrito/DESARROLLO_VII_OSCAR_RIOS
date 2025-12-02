<?php
require_once 'config.php';
require_once 'src/Database.php';
require_once 'src/productos/ProductoManager.php';

$productosManager = new ProductoManager();

try {
    // Obtener productos
    $allProductos = $productoManager->getAllProductos();
    $stats['total_productos'] = count($allProductos);
    
} catch (Exception $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}

$pageTitle = 'Lista de Productos';
$showNav = true;

ob_start();
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
    }
    .stat-card.pending {
        border-left-color: #ffa500;
    }
    .stat-card.approved {
        border-left-color: #28a745;
    }
    .stat-card.rejected {
        border-left-color: #dc3545;
    }
    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
    }
    .section-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .section-header h2 {
        color: #333;
        font-size: 1.5rem;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    .table tr:hover {
        background: #f8f9fa;
    }
    .badge {
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
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }
    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        text-decoration: none;
        color: #333;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .action-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .action-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .action-desc {
        font-size: 0.875rem;
        color: #666;
    }
</style>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Productos</div>
        <div class="stat-value"><?= $stats['total_productos'] ?></div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h2>📋 Productos</h2>
        <a href="<?= url('src/productos') ?>" class="btn btn-primary">Ver Todos</a>
    </div>
    
    <?php if (empty($allProductos)): ?>
        <p>No hay productos registrados aún.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allProductos as $producto): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($producto['id']) ?></strong></td>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['categoria']) ?></td>
                    <td><?= formatMoney($producto['precio']) ?></td>
                    <td><?= formatDate($claim['fecha_registro']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="quick-actions">
    <a href="<?= url('src/productos/view/create.php') ?>" class="action-card">
        <div class="action-icon">➕</div>
        <div class="action-title">Nuevo Producto</div>
        <div class="action-desc">Registrar un nuevo producto</div>
    </a>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>