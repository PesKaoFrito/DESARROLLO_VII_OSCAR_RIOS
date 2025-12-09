<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateRequired($value) {
    return !empty(trim($value));
}

function validateNumeric($value) {
    return is_numeric($value);
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validateMinLength($value, $min) {
    return strlen(trim($value)) >= $min;
}

function validateMaxLength($value, $max) {
    return strlen(trim($value)) <= $max;
}

function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

function redirectTo($url) {
    // Si la URL no comienza con http, agregar BASE_URL
    if (!preg_match('/^https?:\/\//', $url)) {
        $url = url($url);
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Genera una URL bonita según el .htaccess configurado
 * Ejemplos:
 *   url('claims') -> BASE_URL/claims
 *   url('claims/create') -> BASE_URL/claims/create
 *   url('claims/edit/123') -> BASE_URL/claims/edit/123
 */
function url($path = '') {
    $baseUrl = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');
    return $baseUrl . '/' . $path;
}

function asset($path) {
    // Remover / inicial si existe
    $path = ltrim($path, '/');
    // Construir ruta desde BASE_URL
    $base = rtrim(BASE_URL, '/');
    return $base . '/public/' . $path;
}

function formatMoney($amount) {
    return '$' . number_format($amount, 2);
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

function generateClaimNumber() {
    return 'CLM-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function generatePolicyNumber() {
    return 'POL-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * Obtiene la URL actual
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Verifica si la URL actual coincide con un patrón
 */
function isCurrentUrl($pattern) {
    $current = $_SERVER['REQUEST_URI'];
    return strpos($current, $pattern) !== false;
}

/**
 * Traduce los estados al español
 */
function translateStatus($status) {
    $translations = [
        // Estados de reclamos
        'pending' => 'Pendiente',
        'in_review' => 'En Revisión',
        'in-review' => 'En Revisión',
        'approved' => 'Aprobado',
        'rejected' => 'Rechazado',
        'closed' => 'Cerrado',
        // Estados de pólizas
        'active' => 'Activa',
        'expired' => 'Expirada',
        'cancelled' => 'Cancelada',
        'suspended' => 'Suspendida'
    ];
    
    return $translations[strtolower($status)] ?? ucfirst($status);
}

/**
 * Traduce los roles al español
 */
function translateRole($role) {
    $translations = [
        'admin' => 'Administrador',
        'supervisor' => 'Supervisor',
        'analyst' => 'Analista'
    ];
    
    return $translations[strtolower($role)] ?? ucfirst($role);
}
