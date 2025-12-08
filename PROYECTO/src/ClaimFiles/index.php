<?php
require_once __DIR__ . '/..config.php';
require_once __DIR__ . '/..includes/auth.php';
require_once __DIR__ . '/..includes/helpers.php';

requireAuth();

$pageTitle = 'Archivos de Reclamos - Sistema de Gestión';
$showNav = true;

ob_start();
?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-upload"></i> Archivos de Reclamos</h1>
        <p class="subtitle">Gestión de documentos adjuntos</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="no-data">
            <i class="fas fa-folder-open" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3>Módulo en Desarrollo</h3>
            <p>La gestión de archivos estará disponible próximamente.</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/..views/layout.php';
?>
