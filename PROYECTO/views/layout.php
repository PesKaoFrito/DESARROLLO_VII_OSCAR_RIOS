<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema de Gestión de Reclamos' ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isset($showNav) && $showNav): ?>
    <header class="main-header">
        <div class="header-top">
            <div class="header-top-content">
                <div class="contact-info">
                    <a href="tel:+5076000000">
                        <i class="fas fa-phone"></i> +507 6000-0000
                    </a>
                    <a href="mailto:info@seguros.com">
                        <i class="fas fa-envelope"></i> info@seguros.com
                    </a>
                </div>
                <div>
                    <span><i class="fas fa-clock"></i> Lun - Vie: 8:00 AM - 6:00 PM</span>
                </div>
            </div>
        </div>
        <nav class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="<?= url('dashboard') ?>" class="navbar-brand">
                <div class="brand-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem;">SecureLife</div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">Sistema de Gestión</div>
                </div>
            </a>
            <div class="user-dropdown">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div style="font-weight: 600;"><?= getCurrentUser()['name'] ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9;"><?= ucfirst(getCurrentUser()['role']) ?></div>
                </div>
                <a href="<?= url('logout') ?>" class="btn btn-danger" style="margin-left: 1rem;">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </nav>
    </header>
    
    <div class="page-wrapper">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-menu">
                <?php
                // Obtener la URL actual para marcar la página activa
                $currentUrl = $_SERVER['REQUEST_URI'];
                $currentPath = parse_url($currentUrl, PHP_URL_PATH);
                $currentPath = str_replace('/PROYECTO/', '', $currentPath);
                $currentPath = trim($currentPath, '/');
                ?>
                
                <a href="<?= url('claims/create') ?>" class="menu-item primary">
                    <i class="fas fa-file-plus"></i>
                    <span>Nuevo Reclamo</span>
                    <small>Registrar un nuevo reclamo</small>
                </a>
                
                <a href="<?= url('dashboard') ?>" class="menu-item <?= ($currentPath == 'dashboard' || $currentPath == '') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                    <small>Panel principal</small>
                </a>
                
                <a href="<?= url('claims') ?>" class="menu-item <?= (strpos($currentPath, 'claims') === 0) ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Reclamos</span>
                    <small>Gestionar reclamos</small>
                </a>
                
                <a href="<?= url('policies') ?>" class="menu-item <?= (strpos($currentPath, 'policies') === 0) ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Pólizas</span>
                    <small>4 pólizas activas</small>
                </a>
                
                <a href="<?= url('reports') ?>" class="menu-item <?= (strpos($currentPath, 'reports') === 0) ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                    <small>Ver estadísticas y análisis</small>
                </a>
                
                <?php if (hasRole('admin') || hasRole('supervisor')): ?>
                <a href="<?= url('users') ?>" class="menu-item <?= (strpos($currentPath, 'users') === 0) ? 'active' : '' ?>">
                    <i class="fas fa-users-cog"></i>
                    <span>Usuarios</span>
                    <small>Gestionar usuarios</small>
                </a>
                <?php endif; ?>
            </nav>
        </aside>
        
        <main class="main-container">
            <?php echo $content; ?>
        </main>
    </div>
    <?php else: ?>
    <main class="main-container">
        <?php echo $content; ?>
    </main>
    <?php endif; ?>
    
    <?php if (isset($showNav) && $showNav): ?>
    <footer class="main-footer">
        <div class="footer-stats">
            <?php
            // Obtener estadísticas para el footer
            if (!isset($policyManager)) {
                require_once __DIR__ . '/../src/Database.php';
                require_once __DIR__ . '/../src/Policies/PolicyManager.php';
                $policyManager = new PolicyManager();
            }
            $policyStats = $policyManager->getPolicyStats();
            ?>
            <div class="footer-stat">
                <i class="fas fa-shield-alt"></i>
                <strong><?= $policyStats['active'] ?? 0 ?></strong>
                <span>Pólizas Activas</span>
            </div>
            <div class="footer-stat">
                <i class="fas fa-headset"></i>
                <strong>24/7</strong>
                <span>Soporte</span>
            </div>
            <div class="footer-stat">
                <i class="fas fa-award"></i>
                <strong>30+</strong>
                <span>Años de Experiencia</span>
            </div>
            <div class="footer-stat">
                <i class="fas fa-users"></i>
                <strong>10,000+</strong>
                <span>Clientes Satisfechos</span>
            </div>
        </div>
        <div class="footer-content">
            <div class="footer-section">
                <h3>SecureLife Insurance</h3>
                <p>Protegiendo lo que más importa desde 1990. Más de 30 años de experiencia en el mercado panameño.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="<?= url('dashboard') ?>">Dashboard</a></li>
                    <li><a href="<?= url('claims') ?>">Reclamos</a></li>
                    <li><a href="<?= url('policies') ?>">Pólizas</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Calle 50, Ciudad de Panamá</li>
                    <li><i class="fas fa-phone"></i> +507 6000-0000</li>
                    <li><i class="fas fa-envelope"></i> info@securelife.com</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Horarios</h3>
                <ul>
                    <li>Lunes - Viernes: 8:00 AM - 6:00 PM</li>
                    <li>Sábados: 9:00 AM - 1:00 PM</li>
                    <li>Domingos: Cerrado</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> SecureLife Insurance. Todos los derechos reservados.</p>
        </div>
    </footer>
    <?php endif; ?>
    
    <script src="<?= asset('assets/js/main.js') ?>"></script>
    <script>
        // Toggle sidebar en móvil
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
            
            // Cerrar sidebar al hacer clic fuera en móvil
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        }
    </script>
</body>
</html>