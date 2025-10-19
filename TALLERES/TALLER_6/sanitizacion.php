<?php
function sanitizarNombre($nombre) {
    return trim(filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

function sanitizarEmail($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

function sanitizarSitioWeb($sitioWeb) {
    $url = trim($sitioWeb);
    $url = filter_var($url, FILTER_SANITIZE_URL);
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
}

function sanitizarGenero($genero) {
    return trim(filter_var($genero, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

function sanitizarIntereses($intereses) {
    if (!is_array($intereses)) return [];
    return array_map(function($interes) {
        return trim(filter_var($interes, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }, $intereses);
}

function sanitizarComentarios($comentarios) {
    return htmlspecialchars(trim($comentarios), ENT_QUOTES, 'UTF-8');
}

function sanitizarFechaNacimiento($fecha) {
    return trim(filter_var($fecha, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

/* Alias por compatibilidad (opcional): si tu procesar.php usa sanitizarSitio_web */
if (!function_exists('sanitizarSitio_web')) {
    function sanitizarSitio_web($s) {
        return sanitizarSitioWeb($s);
    }
}
?>
