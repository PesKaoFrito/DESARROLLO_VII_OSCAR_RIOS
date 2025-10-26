<?php
require_once 'error_log.php';

try {
    $dsn = "mysql:host=localhost;dbname=taller8_db;charset=utf8mb4";
    $usuario = "root";
    $password = "";
    
    $pdo = new PDO($dsn, $usuario, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    $logger = ErrorLogger::getInstance();
    $logger->logError(
        "Error de conexión: " . $e->getMessage(),
        'PDO_ERROR',
        __FILE__,
        __LINE__
    );
    die("Error de conexión: " . $e->getMessage());
}