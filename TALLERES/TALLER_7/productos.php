<?php
// Lista de productos (simulada, podrías sacarla de una base de datos)
$productos = [
    ["nombre" => "Camiseta Hummingbird Flutist", "precio" => 25.99, "cantidad"=>0],
    ["nombre" => "Throttle Cap", "precio" => 29.50, "cantidad"=>0],
    ["nombre" => "Fast Wings, No Brakes", "precio" => 24.75, "cantidad"=>0],
    ["nombre" => "Birdwatcher Hoodie", "precio" => 35.00, "cantidad"=>0],
    ["nombre" => "Taza Chirp & Chic", "precio" => 15.90, "cantidad"=>0]
];

//Añadir productos al carrito usando sesiones
session_start();
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto'])) {
    $productoSeleccionado = $_POST['producto'];
    foreach ($productos as $p) {
        if ($p['nombre'] === $productoSeleccionado) {
            $p['cantidad'] = $_POST['cantidad'];
            $_SESSION['carrito'][] = $p;
            break;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <style>
        /* Agregar estilos para el formulario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7f8;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .productos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .producto {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 200px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .producto h3 {
            color: #444;
        }
        .precio {
            color: #2b8a3e;
            font-weight: bold;
        }
        .cantidad {
            width: 50px;
            padding: 5px;
            margin-bottom: 10px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>Lista de Productos</h1>

<div class="productos">
    <?php foreach ($productos as $p): ?>
        <div class="producto">
            <h3><?= htmlspecialchars($p["nombre"]) ?></h3>
            <p class="precio">$<?= number_format($p["precio"], 2) ?></p>
            <form method="post" action="">
                <input type="hidden" name="producto" value="<?= htmlspecialchars($p["nombre"]) ?>">
                <input type="number" name="cantidad" value="1" min="1">
                <button type="submit">Añadir al Carrito</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<div class="ver-carrito" style="text-align: center; margin-top: 20px;">
    <a href="ver_carrito.php">Ver Carrito (<?php echo count($_SESSION['carrito']); ?>)</a>
</div>
</body>
</html>