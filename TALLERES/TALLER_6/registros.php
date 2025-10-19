<?php
require_once 'classes/DataStorage.php';

$storage = new DataStorage();
$registros = $storage->obtenerRegistros();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Registros</title>
    <link rel="stylesheet" href="assets/css/forms.css">
</head>
<body>
    <div class="container">
        <h1>Registros Procesados</h1>
        
        <?php if (empty($registros)): ?>
            <p class="info-message">No hay registros almacenados.</p>
        <?php else: ?>
            <?php foreach ($registros as $registro): ?>
                <div class="registro-card">
                    <?php if (isset($registro['foto_perfil']) && file_exists($registro['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($registro['foto_perfil']); ?>" 
                             alt="Foto de perfil" class="registro-foto">
                    <?php endif; ?>
                    
                    <div class="registro-info">
                        <h3><?php echo htmlspecialchars($registro['nombre']); ?></h3>
                        <p>Edad: <?php echo htmlspecialchars($registro['edad']); ?> años</p>
                        <p>Email: <?php echo htmlspecialchars($registro['email']); ?></p>
                        <p>Fecha de registro: <?php echo htmlspecialchars($registro['fecha_registro']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="form-actions">
            <button onclick="window.location.href='formulario.html'" class="btn">Nuevo Registro</button>
        </div>
    </div>
</body>
</html>