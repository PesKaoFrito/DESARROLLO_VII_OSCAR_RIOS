<?php
session_start();
if (!isset($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit();
}

// Eliminar items individuales del carrito


$carrito = $_SESSION['carrito'];
$item_id = $_GET['id'] ?? null;
if ($item_id !== null) {
    foreach ($carrito as $index => $item) {
        if ($item['id'] == $item_id) {
            unset($carrito[$index]);
            $_SESSION['carrito'] = array_values($carrito);
            break;
        }
    }
    header("Location: ver_carrito.php");
    exit();
}
?>