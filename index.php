<?php
/*
 * index.php
 *
 * Un sencillo explorador de archivos para proyectos PHP. Este script permite
 * navegar por los directorios a partir de la ubicación del propio fichero,
 * visualizar listados de archivos y directorios, mostrar el contenido de
 * archivos de texto y descargar cualquier fichero de forma segura. Se han
 * incorporado medidas básicas de seguridad para evitar ataques de recorrido
 * de directorios ("path traversal") y se limita la visualización a archivos
 * relativamente pequeños y de tipo texto o código.
 */

// Directorio raíz desde el cual se permiten las operaciones (ubicación del script)
$root = realpath(__DIR__);

// --- Descarga de archivos ---------------------------------------------------
// Si se solicita la descarga de un archivo mediante el parámetro "download",
// se valida la ruta y se envía el archivo con las cabeceras adecuadas.
if (isset($_GET['download'])) {
    $downloadRel = trim($_GET['download'], '/\\');
    $downloadPath = realpath($root . DIRECTORY_SEPARATOR . $downloadRel);
    if ($downloadPath !== false && strpos($downloadPath, $root) === 0 && is_file($downloadPath)) {
        $filename = basename($downloadPath);
        // Determinar el tipo MIME del archivo. Si falla, usar binario genérico.
        $mime = function_exists('mime_content_type') ? mime_content_type($downloadPath) : 'application/octet-stream';
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($downloadPath));
        flush();
        readfile($downloadPath);
        exit;
    }
    // Si no es válido, redirigir al inicio
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Ejecución de archivos PHP ---------------------------------------------
// Permite ejecutar un archivo PHP cuando se pasa el parámetro "run". La salida
// del script se captura y se muestra en una página independiente. Para evitar
// problemas con cabeceras enviadas por el script incluido, se utiliza
// `shell_exec` para invocar el intérprete de PHP directamente.
if (isset($_GET['run'])) {
    $runRel = trim($_GET['run'], '/\\');
    $runPath = realpath($root . DIRECTORY_SEPARATOR . $runRel);
    // Validar que la ruta exista, pertenezca al árbol permitido y sea un archivo
    if ($runPath !== false && strpos($runPath, $root) === 0 && is_file($runPath)) {
        // Redirigir al archivo solicitado para que el servidor web lo sirva
        header('Location: ' . str_replace(DIRECTORY_SEPARATOR, '/', $runRel));
        exit;
    }
    // Si la ruta no es válida, redirigir a la raíz del explorador
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Navegación por directorios y visualización de archivos -----------------
// Recoger el parámetro "path" para determinar la ruta relativa dentro del raíz
$relPath = isset($_GET['path']) ? $_GET['path'] : '';
$relPath = trim($relPath, '/\\');

// Construir la ruta absoluta y normalizar mediante realpath
$fullPath = $root;
if ($relPath !== '') {
    $candidate = realpath($root . DIRECTORY_SEPARATOR . $relPath);
    if ($candidate !== false) {
        $fullPath = $candidate;
    }
}

// Verificar que la ruta obtenida está dentro del directorio raíz
if ($fullPath === false || strpos($fullPath, $root) !== 0) {
    // Ruta inválida: volver al directorio raíz
    $fullPath = $root;
    $relPath = '';
}

// Si la ruta corresponde a un archivo, mostrar contenido detallado
if (is_file($fullPath)) {
    $filename = basename($fullPath);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $fileSize = filesize($fullPath);
    // Definir extensiones que se consideran de texto/código para visualización
    $viewableExtensions = ['php','phtml','html','htm','css','js','json','txt','md','xml','yml','yaml','csv'];
    $canView = in_array($ext, $viewableExtensions) && $fileSize < 5 * 1024 * 1024; // 5MB
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Explorador - <?php echo htmlspecialchars($filename); ?></title>
        <style>
            :root {
                --primary: #2563eb;
                --bg: #f8fafc;
                --text: #0f172a;
                --muted: #64748b;
                --border: #e2e8f0;
                --hover: #eff6ff;
                --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            }
            
            body {
                font-family: system-ui, -apple-system, sans-serif;
                margin: 0;
                padding: 2rem;
                background: var(--bg);
                color: var(--text);
                line-height: 1.5;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
            }

            h1 {
                font-size: 1.875rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                color: var(--text);
            }

            .breadcrumbs {
                background: white;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                box-shadow: var(--shadow);
                margin-bottom: 1.5rem;
            }

            .breadcrumbs a {
                color: var(--primary);
                text-decoration: none;
                margin-right: 0.5rem;
            }

            .breadcrumbs span {
                color: var(--muted);
                margin: 0 0.5rem;
            }

            table {
                width: 100%;
                background: white;
                border-radius: 0.5rem;
                box-shadow: var(--shadow);
                margin: 1.5rem 0;
            }

            th, td {
                padding: 1rem;
                border-bottom: 1px solid var(--border);
                text-align: left;
            }

            th {
                background: var(--bg);
                font-weight: 600;
                color: var(--muted);
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            tr:last-child td {
                border-bottom: none;
            }

            tr:hover {
                background: var(--hover);
            }

            a {
                color: var(--primary);
                text-decoration: none;
                transition: color 0.15s ease;
            }

            a:hover {
                text-decoration: underline;
            }

            .file-icon {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .action-links {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                margin-left: 1rem;
                font-size: 0.875rem;
            }

            .action-links a {
                color: var(--muted);
            }

            pre {
                background: var(--bg);
                border-radius: 0.5rem;
                padding: 1.5rem;
                overflow: auto;
                font-size: 0.875rem;
                line-height: 1.7;
                margin: 1.5rem 0;
            }

            .file-info {
                background: white;
                padding: 1.5rem;
                border-radius: 0.5rem;
                box-shadow: var(--shadow);
                margin-bottom: 1.5rem;
            }

            .back-button {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: white;
                border: 1px solid var(--border);
                border-radius: 0.375rem;
                color: var(--text);
                font-size: 0.875rem;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.15s ease;
            }

            .back-button:hover {
                background: var(--hover);
                text-decoration: none;
            }
        </style>
    </head>
    <body>
    <div class="breadcrumbs">
        <?php
        // Generar migas de pan para navegación
        $crumbs = $relPath !== '' ? explode('/', $relPath) : [];
        $crumbPath = '';
        echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">Raíz</a> ';
        foreach ($crumbs as $index => $crumb) {
            $crumbPath .= $crumb . '/';
            if ($index < count($crumbs) - 1) {
                echo '&gt; <a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode(rtrim($crumbPath, '/'))) . '">' . htmlspecialchars($crumb) . '</a> ';
            } else {
                // Último elemento (archivo) sin enlace
                echo '&gt; <span>' . htmlspecialchars($crumb) . '</span>';
            }
        }
        ?>
    </div>
    <h2><?php echo htmlspecialchars($filename); ?></h2>
    <p>Tamaño: <?php echo number_format($fileSize); ?> bytes</p>
    <p>
        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode(dirname($relPath))); ?>">⬅ Regresar</a>
        | <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?download=' . urlencode($relPath)); ?>">Descargar</a>
        | <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?run=' . urlencode($relPath)); ?>">Ejecutar</a>
    </p>
    <?php
    if ($canView) {
        // Mostrar el contenido con formato según tipo
        if ($ext === 'php' || $ext === 'phtml') {
            // Resaltar sintaxis PHP
            echo '<pre>' . highlight_file($fullPath, true) . '</pre>';
        } else {
            // Leer como texto plano
            $contents = file_get_contents($fullPath);
            echo '<pre>' . htmlspecialchars($contents) . '</pre>';
        }
    } else {
        echo '<p>No se puede mostrar el contenido de este archivo. Utilice el enlace de descarga.</p>';
    }
    ?>
    </body>
    </html>
    <?php
    exit;
}

// Si no es un archivo, debe ser un directorio: listar su contenido
$items = scandir($fullPath);
// Determinar la ruta relativa del directorio padre (si no estamos en la raíz)
$parentRel = '';
if ($fullPath !== $root) {
    $parentRel = dirname($relPath);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Explorador de Archivos</title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --hover: #eff6ff;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        
        body {
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 2rem;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .breadcrumbs {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .breadcrumbs a {
            color: var(--primary);
            text-decoration: none;
            margin-right: 0.5rem;
        }

        .breadcrumbs span {
            color: var(--muted);
            margin: 0 0.5rem;
        }

        table {
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            margin: 1.5rem 0;
        }

        th, td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        th {
            background: var(--bg);
            font-weight: 600;
            color: var(--muted);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: var(--hover);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        a:hover {
            text-decoration: underline;
        }

        .file-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-links {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: 1rem;
            font-size: 0.875rem;
        }

        .action-links a {
            color: var(--muted);
        }

        pre {
            background: var(--bg);
            border-radius: 0.5rem;
            padding: 1.5rem;
            overflow: auto;
            font-size: 0.875rem;
            line-height: 1.7;
            margin: 1.5rem 0;
        }

        .file-info {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .back-button:hover {
            background: var(--hover);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Explorador de Archivos</h1>
        
        <div class="breadcrumbs">
            <?php
            // Migas de pan para directorios
            $crumbs = $relPath !== '' ? explode('/', $relPath) : [];
            $crumbPath = '';
            echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">Raíz</a> ';
            foreach ($crumbs as $crumb) {
                $crumbPath .= $crumb . '/';
                echo '&gt; <a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode(rtrim($crumbPath, '/'))) . '">' . htmlspecialchars($crumb) . '</a> ';
            }
            ?>
        </div>
        <?php if ($fullPath !== $root): ?>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode($parentRel)); ?>" class="back-button">
                ← Subir un nivel
            </a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tamaño</th>
                    <th>Tipo</th>
                    <th>Modificado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <?php
                // Omitir entradas especiales
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $itemRelPath = ($relPath !== '' ? $relPath . '/' : '') . $item;
                $itemFullPath = realpath($fullPath . DIRECTORY_SEPARATOR . $item);
                // Comprobar que la ruta es válida y pertenece al árbol permitido
                if ($itemFullPath === false || strpos($itemFullPath, $root) !== 0) {
                    continue;
                }
                $isDir = is_dir($itemFullPath);
                $isFile = is_file($itemFullPath);
                $size = $isFile ? filesize($itemFullPath) : '';
                $modTime = date('Y-m-d H:i', filemtime($itemFullPath));
                ?>
                <tr>
                    <td>
                        <div class="file-icon">
                            <?php if ($isDir): ?>
                                <span>📁</span>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode($itemRelPath)); ?>">
                                    <?php echo htmlspecialchars($item); ?>
                                </a>
                            <?php elseif ($isFile): ?>
                                <span>📄</span>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?path=' . urlencode($itemRelPath)); ?>">
                                    <?php echo htmlspecialchars($item); ?>
                                </a>
                                <div class="action-links">
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?run=' . urlencode($itemRelPath)); ?>">Ejecutar</a>
                                </div>
                            <?php else: ?>
                                <?php echo htmlspecialchars($item); ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo ($size !== '' ? number_format($size) . ' bytes' : '-'); ?></td>
                    <td><?php echo ($isDir ? 'Directorio' : 'Archivo'); ?></td>
                    <td><?php echo $modTime; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>