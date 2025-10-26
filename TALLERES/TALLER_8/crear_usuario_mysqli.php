<?php
require_once 'config_mysqli.php';
require_once 'error_log.php';

$nombre = "Juan";
$email = "juan@example.com";

$query = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
$stmt = $mysqli->prepare($query);

if ($stmt === false) {
    $logger = ErrorLogger::getInstance();
    $logger->logDatabaseError(
        "Error al preparar la consulta: " . $mysqli->error,
        $query
    );
    die("Error al preparar la consulta");
}

$stmt->bind_param("ss", $nombre, $email);

if (!$stmt->execute()) {
    $logger = ErrorLogger::getInstance();
    $logger->logDatabaseError(
        "Error al ejecutar la consulta: " . $stmt->error,
        $query,
        ['nombre' => $nombre, 'email' => $email]
    );
    echo "Error al crear el usuario";
}

$mysqli->close();
?>
