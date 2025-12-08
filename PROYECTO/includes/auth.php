<?php
// Iniciar sesión
session_start();

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Función para verificar si el usuario tiene un rol específico
function hasRole($role) {
    return isAuthenticated() && $_SESSION['user_role'] === $role;
}

// Función para requerir autenticación
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit;
    }
}

// Función para requerir un rol específico (acepta string o array)
function requireRole($roles) {
    requireAuth();
    
    // Convertir a array si es string
    $rolesArray = is_array($roles) ? $roles : [$roles];
    
    // Verificar si el usuario tiene alguno de los roles permitidos
    $hasPermission = in_array($_SESSION['user_role'], $rolesArray);
    
    if (!$hasPermission) {
        $_SESSION['error'] = 'No tienes permisos para acceder a esta página';
        header('Location: ' . BASE_URL . 'dashboard.php');
        exit;
    }
}

// Función para obtener el usuario actual
function getCurrentUser() {
    if (!isAuthenticated()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role']
    ];
}

// Función para hacer login
function login($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
}

// Función para hacer logout
function logout() {
    // Asegurar que la sesión está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Limpiar todas las variables de sesión
    $_SESSION = [];
    
    // Destruir la cookie de sesión
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destruir la sesión
    session_destroy();
}
