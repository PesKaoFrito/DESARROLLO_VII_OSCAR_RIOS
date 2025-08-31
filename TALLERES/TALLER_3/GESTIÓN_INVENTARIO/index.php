<?php
    include 'includes/funciones.php';
    include 'includes/header.php';
    $rutaInventario = "inventario.json";
    $obtenerInventario = obtenerInventario($rutaInventario);
    if (empty($obtenerInventario)) {
        echo "No hay productos en el inventario.";
    }
    else {
        $ordenarInventario = ordenarInventario($obtenerInventario, 'nombre', 'asc');
        $formatearInventario = formatearInventario($ordenarInventario,"Inventario:");
        echo $formatearInventario;
    }
    $valorTotalInventario = valorTotalInventario($obtenerInventario);
    echo "<h3>Valor Total del Inventario: \$$valorTotalInventario</h3>";
    ?></br>
    <?php
    $filtrarProductosLowStock = filtrarProductosLowStock($obtenerInventario, 5);
    if (empty($filtrarProductosLowStock)) {
        echo "<h3>No hay productos en bajo stock.</h3>";
    } else {
        $filtrarProductosLowStock = formatearInventario($filtrarProductosLowStock,"Bajo Stock:");
        echo $filtrarProductosLowStock;
    }

    include 'includes/footer.php';
    ?>
