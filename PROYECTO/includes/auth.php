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

// Función para requerir un rol específico
function requireRole($role) {
    requireAuth();
    if (!hasRole($role)) {
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
    session_unset();
    session_destroy();
}
