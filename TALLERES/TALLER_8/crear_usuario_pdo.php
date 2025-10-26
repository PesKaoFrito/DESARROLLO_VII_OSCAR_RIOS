<?php
require_once 'config_pdo.php';

try {
    $query = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $params = ['Juan', 'juan@example.com'];
    
    $stmt = $pdo->prepare($query);
    if (!$stmt->execute($params)) {
        throw new PDOException("Error al insertar usuario");
    }
} catch (PDOException $e) {
    $logger = ErrorLogger::getInstance();
    $logger->logDatabaseError(
        "Error al crear usuario: " . $e->getMessage(),
        $query ?? '',
        $params ?? []
    );
    echo "Error al crear el usuario";
}

unset($pdo);
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div><label>Nombre</label><input type="text" name="nombre" required></div>
    <div><label>Email</label><input type="email" name="email" required></div>
    <input type="submit" value="Crear Usuario">
</form>