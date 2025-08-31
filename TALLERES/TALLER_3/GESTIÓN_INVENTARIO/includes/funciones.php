<?php
    function obtenerInventario($inventario) {   
        return json_decode(file_get_contents($inventario), true);
    }

    function ordenarInventario($inventario, $clave, $orden = 'asc') {
        usort($inventario, function($a, $b) use ($clave, $orden) {
            if ($orden === 'asc') {
                return $a[$clave] <=> $b[$clave];
            } else {
                return $b[$clave] <=> $a[$clave];
            }
        });
        return $inventario;
    }
    function formatearInventario($inventario,$title) {
    $salida = "<h2>$title</h2>";
    $salida .= "<table class='table'>";
    $salida .= "<tr><th>Nombre</th><th>Precio</th><th>Cantidad</th></tr>";
    foreach ($inventario as $item) {
        $salida .= "<tr><td>{$item['nombre']}</td><td>\${$item['precio']}</td><td>{$item['cantidad']}</td></tr>";
    }
    $salida .= "</table>";
    return $salida;
    }
    function filtrarProductosLowStock($inventario, $cantidadMinima) {
    return array_filter($inventario, function($item) use ($cantidadMinima) {
        if (!isset($item['cantidad'])) {
            return false;
        }
        return $item['cantidad'] < $cantidadMinima;
        });
    }
    function valorTotalInventario($inventario) {
    return array_reduce($inventario, function($total, $item) {
        return round($total + ($item['precio'] * $item['cantidad']), 2);
    }, 0);
}
?>