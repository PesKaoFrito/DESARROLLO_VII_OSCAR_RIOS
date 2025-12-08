<?php
require_once 'config.php';
require_once 'includes/helpers.php';

// Si ya está autenticado, redirigir al dashboard
if (function_exists('isAuthenticated') && isAuthenticated()) {
    header('Location: ' . url('dashboard'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureLife Insurance - Sistema de Gestión de Reclamos</title>
    <link rel="stylesheet" href="<?= asset('assets/css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header" style="position: sticky; top: 0; z-index: 1000;">
        <nav class="navbar">
            <a href="index.php" class="navbar-brand">
                <div class="brand-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div style="font-size: 1.5rem;">SecureLife</div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">Insurance Solutions</div>
                </div>
            </a>
            <div class="navbar-menu">
                <a href="#servicios"><i class="fas fa-briefcase"></i> Servicios</a>
                <a href="#nosotros"><i class="fas fa-users"></i> Nosotros</a>
                <a href="#contacto"><i class="fas fa-envelope"></i> Contacto</a>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="<?= url('login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section" style="min-height: 90vh; display: flex; align-items: center;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; width: 100%;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
                <div>
                    <h1 style="font-size: 3.5rem; line-height: 1.2; margin-bottom: 1.5rem;">
                        Protegiendo tu Futuro con <span style="color: var(--accent-color);">Confianza</span>
                    </h1>
                    <p style="font-size: 1.25rem; opacity: 0.9; margin-bottom: 2rem;">
                        Sistema integral de gestión de reclamos de seguros. Rápido, seguro y eficiente.
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="<?= url('auth/login.php') ?>" class="btn btn-light" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                            <i class="fas fa-rocket"></i> Comenzar Ahora
                        </a>
                        <a href="#servicios" class="btn btn-outline" style="padding: 1rem 2.5rem; font-size: 1.1rem; background: rgba(255,255,255,0.2); border: 2px solid white;">
                            <i class="fas fa-info-circle"></i> Más Información
                        </a>
                    </div>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-shield-alt" style="font-size: 20rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Indicators -->
    <div class="trust-indicators" style="background: white; padding: 3rem 2rem;">
        <div class="trust-item">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>100% Seguro</strong>
                <span>Datos protegidos</span>
            </div>
        </div>
        <div class="trust-item">
            <i class="fas fa-clock"></i>
            <div>
                <strong>Soporte 24/7</strong>
                <span>Atención continua</span>
            </div>
        </div>
        <div class="trust-item">
            <i class="fas fa-award"></i>
            <div>
                <strong>30+ Años</strong>
                <span>De experiencia</span>
            </div>
        </div>
        <div class="trust-item">
            <i class="fas fa-users"></i>
            <div>
                <strong>10,000+ Clientes</strong>
                <span>Satisfechos</span>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <section id="servicios" style="padding: 6rem 2rem; background: #f8f9fa;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <h2 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;">
                    Nuestros Servicios
                </h2>
                <p style="font-size: 1.1rem; color: #666;">
                    Soluciones integrales para la gestión de tus reclamos
                </p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Gestión de Reclamos</h3>
                    <p>Sistema completo para registrar, procesar y dar seguimiento a todos tus reclamos de manera eficiente.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-file-contract"></i>
                    <h3>Administración de Pólizas</h3>
                    <p>Gestiona todas tus pólizas de seguro en un solo lugar con acceso rápido a la información.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Reportes y Análisis</h3>
                    <p>Genera reportes detallados y obtén insights valiosos sobre el estado de tus reclamos.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-clock"></i>
                    <h3>Procesamiento Rápido</h3>
                    <p>Reducimos el tiempo de procesamiento con flujos de trabajo automatizados y eficientes.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Seguridad Total</h3>
                    <p>Protegemos tu información con los más altos estándares de seguridad y encriptación.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-headset"></i>
                    <h3>Soporte Dedicado</h3>
                    <p>Nuestro equipo está disponible 24/7 para asistirte con cualquier consulta o problema.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="nosotros" style="padding: 6rem 2rem; background: white;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
                <div>
                    <h2 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
                        ¿Por qué elegir SecureLife?
                    </h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: #666; margin-bottom: 1.5rem;">
                        Con más de 30 años de experiencia en el mercado de seguros, SecureLife se ha consolidado 
                        como líder en soluciones de gestión de reclamos en Panamá.
                    </p>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.5rem;"></i>
                            <span style="font-size: 1.05rem;">Procesamiento rápido de reclamos</span>
                        </li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.5rem;"></i>
                            <span style="font-size: 1.05rem;">Interfaz intuitiva y fácil de usar</span>
                        </li>
                        <li style="padding: 0.75rem 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.5rem;"></i>
                            <span style="font-size: 1.05rem;">Reportes detallados en tiempo real</span>
                        </li>
                        <li style="padding: 0.75rem 0; display: flex; align-items: center; gap: 1rem;">
                            <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.5rem;"></i>
                            <span style="font-size: 1.05rem;">Soporte técnico especializado</span>
                        </li>
                    </ul>
                </div>
                <div style="text-align: center; color: var(--primary-color);">
                    <i class="fas fa-handshake" style="font-size: 18rem; opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" style="padding: 6rem 2rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">
                ¿Listo para comenzar?
            </h2>
            <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 2.5rem;">
                Únete a miles de clientes satisfechos que confían en SecureLife para gestionar sus reclamos.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
                <a href="<?= url('auth/login.php') ?>" class="btn btn-light" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                    <i class="fas fa-sign-in-alt"></i> Acceder al Sistema
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-top: 4rem; padding-top: 3rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <div>
                    <i class="fas fa-map-marker-alt" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Dirección</h4>
                    <p style="opacity: 0.9;">Calle 50, Ciudad de Panamá</p>
                </div>
                <div>
                    <i class="fas fa-phone" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Teléfono</h4>
                    <p style="opacity: 0.9;">+507 6000-0000</p>
                </div>
                <div>
                    <i class="fas fa-envelope" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Email</h4>
                    <p style="opacity: 0.9;">info@securelife.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>SecureLife Insurance</h3>
                <p>Protegiendo lo que más importa desde 1990. Más de 30 años de experiencia en el mercado panameño.</p>
                <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                    <a href="#" style="color: white; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#nosotros">Sobre Nosotros</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="<?= url('auth/login.php') ?>">Iniciar Sesión</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Servicios</h3>
                <ul>
                    <li><a href="#">Seguros de Vida</a></li>
                    <li><a href="#">Seguros de Salud</a></li>
                    <li><a href="#">Seguros de Vehículos</a></li>
                    <li><a href="#">Seguros de Hogar</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Calle 50, Ciudad de Panamá</li>
                    <li><i class="fas fa-phone"></i> +507 6000-0000</li>
                    <li><i class="fas fa-envelope"></i> info@securelife.com</li>
                    <li><i class="fas fa-clock"></i> Lun - Vie: 8:00 AM - 6:00 PM</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> SecureLife Insurance. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // Smooth scroll para los enlaces del menú
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
