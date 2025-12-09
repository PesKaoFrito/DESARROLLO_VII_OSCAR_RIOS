<?php
require 'config.php';
$db = Database::getInstance()->getConnection();

echo "=== CATEGORÍAS ===\n";
$stmt = $db->query('SELECT id, name FROM categories');
while($row = $stmt->fetch()) {
    echo $row['id'] . ' - ' . $row['name'] . "\n";
}

echo "\n=== ESTADOS ===\n";
$stmt = $db->query('SELECT id, name FROM statuses');
while($row = $stmt->fetch()) {
    echo $row['id'] . ' - ' . $row['name'] . "\n";
}

echo "\n=== USUARIOS ===\n";
$stmt = $db->query('SELECT id, name, role FROM users');
while($row = $stmt->fetch()) {
    echo $row['id'] . ' - ' . $row['name'] . ' (' . $row['role'] . ")\n";
}
