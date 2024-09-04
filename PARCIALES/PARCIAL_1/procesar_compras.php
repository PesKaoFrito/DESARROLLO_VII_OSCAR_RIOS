<?php
// procesar_compras.php

// Incluimos el archivo funciones_tienda.php
require_once 'funciones_tienda.php';

// Creamos un array asociativo con los productos y sus precios
$productos = [
    'camisa' => 50,
    'pantalon' => 70,
    'zapatos' => 80,
    'calcetines' => 10,
    'gorra' => 25
];

// Creamos un array asociativo que simula el carrito de compras
$carrito = [
    'camisa' => 2,
    'pantalon' => 1,
    'zapatos' => 1,
    'calcetines' => 3,
    'gorra' => 0
];

// Calculamos el subtotal de la compra
$subtotal = 0;
foreach ($carrito as $producto => $cantidad) {
    $subtotal += $productos[$producto] * $cantidad;
}

// Calculamos el descuento y el impuesto
$descuento = calcular_descuento($subtotal);
$impuesto = aplicar_impuesto($subtotal);

// Calculamos el total a pagar
$total = calcular_total($subtotal, $descuento, $impuesto);

// Mostramos un resumen en formato HTML
echo '<h1>Resumen de Compra </h1>
<h2>Productos Comprados: </h2>
<ul>';
foreach ($carrito as $producto => $cantidad) {
    echo "<li>$producto (Cantidad: $cantidad) - Precio unitario: $" . $productos[$producto] . "</li>";
}
echo "</ul>
<h2>Detalles:</h2>
<p>Subtotal: $subtotal; </p>
<p>Descuento aplicado: $descuento; </p>
<p>Impuesto:  $impuesto; </p>
<h2>Total a Pagar:</h2>
<p>$total; </p>";
?>