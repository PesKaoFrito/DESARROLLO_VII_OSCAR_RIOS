<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include configuration
require_once 'config.php';
require_once 'includes/auth.php';

// Redirigir según autenticación
if (isAuthenticated()) {
    header('Location: ' . BASE_URL . 'dashboard.php');
} else {
    header('Location: ' . BASE_URL . 'auth/login.php');
}
exit;