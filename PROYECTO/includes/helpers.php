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
    header('Location: ' . $url);
    exit;
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
