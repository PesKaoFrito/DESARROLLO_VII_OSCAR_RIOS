<?php
require_once 'config.php';
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

// Obtener la URL solicitada
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

// Si está vacío, mostrar página de inicio
if (empty($url)) {
    require_once 'index.php';
    exit;
}

// Definir las rutas y sus destinos
$routes = [
    // Dashboard
    'dashboard' => 'dashboard.php',
    
    // Autenticación
    'login' => 'auth/login.php',
    'logout' => 'auth/logout.php',
    'register' => 'auth/register.php',
    
    // Claims (Reclamos)
    'claims' => 'src/Claims/index.php',
    'claims/create' => 'src/Claims/views/create.php',
    'claims/view' => 'src/Claims/views/view.php',
    'claims/edit' => 'src/Claims/views/edit.php',
    
    // Policies (Pólizas)
    'policies' => 'src/Policies/index.php',
    'policies/create' => 'src/Policies/views/create.php',
    'policies/view' => 'src/Policies/views/view.php',
    'policies/edit' => 'src/Policies/views/edit.php',
    
    // Reports (Reportes)
    'reports' => 'src/Reports/index.php',
    
    // Users (Usuarios)
    'users' => 'src/Users/index.php',
    'users/create' => 'src/Users/views/create.php',
    'users/view' => 'src/Users/views/view.php',
    'users/edit' => 'src/Users/views/edit.php',
    
    // Categories
    'categories' => 'src/Categories/index.php',
    
    // Statuses
    'statuses' => 'src/Statuses/index.php',
];

// Buscar la ruta en el array
if (array_key_exists($url, $routes)) {
    $file = $routes[$url];
    
    // Verificar si el archivo existe
    if (file_exists($file)) {
        require_once $file;
        exit;
    }
}

// Si la URL incluye un ID (ej: claims/view?id=1)
if (strpos($url, '/') !== false) {
    $parts = explode('/', $url);
    $module = $parts[0];
    $action = $parts[1] ?? 'index';
    
    $routeKey = "$module/$action";
    if (array_key_exists($routeKey, $routes)) {
        $file = $routes[$routeKey];
        if (file_exists($file)) {
            require_once $file;
            exit;
        }
    }
}

// Si no se encuentra la ruta, mostrar error 404
http_response_code(404);
$pageTitle = 'Página no encontrada - SecureLife';
$showNav = false;

ob_start();
?>
<div style="min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem;">
    <div class="card" style="max-width: 600px; text-align: center; padding: 3rem;">
        <div style="margin-bottom: 2rem;">
            <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);">
                <i class="fas fa-exclamation-triangle" style="font-size: 3.5rem; color: white;"></i>
            </div>
            <h1 style="font-size: 5rem; font-weight: 700; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 1rem;">404</h1>
            <h2 style="font-size: 1.75rem; color: var(--dark-text); margin-bottom: 1rem;">Página no encontrada</h2>
            <p style="font-size: 1.1rem; color: var(--light-text); margin-bottom: 2rem; line-height: 1.6;">
                Lo sentimos, la página que estás buscando no existe o ha sido movida. Por favor, verifica la URL o regresa al inicio.
            </p>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= url('dashboard') ?>" class="btn btn-primary" style="padding: 0.875rem 2rem;">
                <i class="fas fa-home"></i> Ir al Dashboard
            </a>
            <?php else: ?>
            <a href="<?= url('') ?>" class="btn btn-primary" style="padding: 0.875rem 2rem;">
                <i class="fas fa-home"></i> Ir al Inicio
            </a>
            <a href="<?= url('login') ?>" class="btn btn-secondary" style="padding: 0.875rem 2rem;">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <p style="font-size: 0.9rem; color: var(--light-text);">
                <i class="fas fa-question-circle"></i> ¿Necesitas ayuda? 
                <a href="mailto:soporte@securelife.com" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                    Contáctanos
                </a>
            </p>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require 'views/layout.php';
