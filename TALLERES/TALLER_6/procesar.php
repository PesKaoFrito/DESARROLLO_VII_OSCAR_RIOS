<?php
session_start();
require_once 'validaciones.php';
require_once 'sanitizacion.php';
require_once 'classes/DataStorage.php';

// Inicializar variables
$errores = [];
$datos = [];
$mensaje = '';

// Procesar solo si es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cambiar 'edad' por 'fecha_nacimiento' en el array de campos
    $campos = ['nombre', 'fecha_nacimiento', 'email', 'sitio_web', 'genero', 'intereses', 'comentarios'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];
            
            // Convertir nombre_campo a NombreCampo para las funciones
            $sufijo = str_replace(' ', '', ucwords(str_replace('_', ' ', $campo)));
            $sanitizarFn = 'sanitizar' . $sufijo;
            $validarFn = 'validar' . $sufijo;
            
            if (function_exists($sanitizarFn)) {
                $valorSanitizado = $sanitizarFn($valor);
            } else {
                $valorSanitizado = is_array($valor) ? 
                    array_map('htmlspecialchars', $valor) : 
                    htmlspecialchars(trim($valor));
            }
            
            $datos[$campo] = $valorSanitizado;
            
            if (function_exists($validarFn)) {
                if (!$validarFn($valorSanitizado)) {
                    $errores[] = "El campo " . ucfirst(str_replace('_', ' ', $campo)) . " no es válido.";
                }
            }
        }
    }

    // Calcular edad después de validar fecha_nacimiento
    if (isset($datos['fecha_nacimiento']) && empty($errores)) {
        $datos['edad'] = calcularEdad($datos['fecha_nacimiento']);
    }

    // Procesar foto con nombre único
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (validarFotoPerfil($_FILES['foto_perfil'])) {
            $directorio_subida = 'uploads/';
            do {
                $nombre_archivo = uniqid() . '_' . basename($_FILES['foto_perfil']['name']);
                $ruta_foto = $directorio_subida . $nombre_archivo;
            } while (file_exists($ruta_foto));
            
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_foto)) {
                $foto_subida = true;
                $datos['foto_perfil'] = $ruta_foto;
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        } else {
            $errores[] = "La foto de perfil debe ser una imagen JPG, PNG o GIF de máximo 2MB.";
        }
    }

    // Si hay errores, guardar datos en sesión para rellenar el formulario
    if (!empty($errores)) {
        $_SESSION['old_data'] = $_POST;
        $_SESSION['errores'] = $errores;
        header('Location: formulario.html');
        exit;
    } else {
        // Guardar datos en JSON si todo está correcto
        $storage = new DataStorage();
        $storage->guardarRegistro($datos);
        unset($_SESSION['old_data']);
        unset($_SESSION['errores']);
        $mensaje = "<div class='success-message'>¡Registro completado con éxito!</div>";
        $foto_subida = isset($datos['foto_perfil']);
        $ruta_foto = $datos['foto_perfil'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesamiento de Formulario</title>
    <link rel="stylesheet" href="assets/css/forms.css">
</head>
<body>
    <div class="container">
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            
            <?php if (!empty($mensaje)): ?>
                <?php echo $mensaje; ?>
                <div class="form-group">
                    <?php if ($foto_subida): ?>
                        <div class="profile-photo">
                            <img src="<?php echo htmlspecialchars($ruta_foto); ?>" 
                                 alt="Foto de perfil" 
                                 style="max-width: 200px; border-radius: 8px; margin: 10px 0;">
                        </div>
                    <?php endif; ?>
                    <?php foreach ($datos as $campo => $valor): ?>
                        <?php if ($campo !== 'foto_perfil'): ?>
                            <p>
                                <strong><?php echo ucfirst(str_replace('_', ' ', $campo)); ?>:</strong>
                                <?php 
                                if (is_array($valor)) {
                                    echo implode(", ", $valor);
                                } else {
                                    echo $valor;
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <?php foreach ($errores as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <button onclick="window.location.href='formulario.html'" class="btn">Volver al formulario</button>
            </div>
            
        <?php else: ?>
            <div class="error-message">
                <p>Acceso no permitido. Por favor, utilice el formulario.</p>
            </div>
            <div class="form-actions">
                <button onclick="window.location.href='formulario.html'" class="btn">Ir al formulario</button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>