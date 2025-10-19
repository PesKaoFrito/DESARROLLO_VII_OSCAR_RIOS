<?php
require_once 'classes/DataStorage.php';
require_once 'validaciones.php';  // Añadir esta línea

$storage = new DataStorage();
$registros = $storage->obtenerRegistros();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización de Registros</title>
    <link rel="stylesheet" href="assets/css/forms.css">
    <style>
        .registros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            padding: 20px 0;
        }

        .registro-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .registro-card:hover {
            transform: translateY(-5px);
        }

        .registro-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .registro-foto {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .registro-nombre {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--text);
        }

        .registro-fecha {
            color: var(--muted);
            font-size: 0.875rem;
        }

        .registro-datos {
            display: grid;
            gap: 8px;
        }

        .dato-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
        }

        .dato-label {
            color: var(--muted);
            font-weight: 500;
        }

        .dato-valor {
            color: var(--text);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .btn-nuevo {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .btn-nuevo:hover {
            background: #1d4ed8;
            text-decoration: none;
        }

        .no-registros {
            text-align: center;
            padding: 40px;
            color: var(--muted);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1>Registros Almacenados</h1>
            <a href="formulario.html" class="btn-nuevo">Nuevo Registro</a>
        </div>

        <?php if (empty($registros)): ?>
            <div class="no-registros">
                <p>No hay registros almacenados.</p>
            </div>
        <?php else: ?>
            <div class="registros-grid">
                <?php foreach ($registros as $registro): ?>
                    <div class="registro-card">
                        <div class="registro-header">
                            <?php if (isset($registro['foto_perfil']) && file_exists($registro['foto_perfil'])): ?>
                                <img src="<?php echo htmlspecialchars($registro['foto_perfil']); ?>" 
                                     alt="Foto de perfil" 
                                     class="registro-foto">
                            <?php endif; ?>
                            <div>
                                <h3 class="registro-nombre">
                                    <?php echo htmlspecialchars($registro['nombre'] ?? ''); ?>
                                </h3>
                                <span class="registro-fecha">
                                    Registrado: <?php echo isset($registro['fecha_registro']) ? 
                                        date('d/m/Y H:i', strtotime($registro['fecha_registro'])) : ''; ?>
                                </span>
                            </div>
                        </div>

                        <div class="registro-datos">
                            <?php if (isset($registro['email'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Email:</span>
                                <span class="dato-valor"><?php echo htmlspecialchars($registro['email']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($registro['edad'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Edad:</span>
                                <span class="dato-valor"><?php echo htmlspecialchars($registro['edad']); ?> años</span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($registro['sitio_web'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Sitio Web:</span>
                                <span class="dato-valor">
                                    <a href="<?php echo htmlspecialchars($registro['sitio_web']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($registro['sitio_web']); ?>
                                    </a>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($registro['genero'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Género:</span>
                                <span class="dato-valor"><?php echo htmlspecialchars($registro['genero']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($registro['intereses']) && is_array($registro['intereses'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Intereses:</span>
                                <span class="dato-valor">
                                    <?php echo htmlspecialchars(implode(', ', $registro['intereses'])); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($registro['comentarios'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Comentarios:</span>
                                <span class="dato-valor"><?php echo nl2br(htmlspecialchars($registro['comentarios'])); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($registro['fecha_nacimiento'])): ?>
                            <div class="dato-item">
                                <span class="dato-label">Fecha de Nacimiento:</span>
                                <span class="dato-valor">
                                    <?php 
                                        echo date('d/m/Y', strtotime($registro['fecha_nacimiento']));
                                        $edad = calcularEdad($registro['fecha_nacimiento']);
                                        echo " ({$edad} años)";
                                    ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>