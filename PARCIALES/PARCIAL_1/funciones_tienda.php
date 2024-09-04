<?php

// Función para calcular el descuento según el subtotal de la compra
function calcular_descuento($subtotal_compra) {
    if ($subtotal_compra < 100) {
        return 0;
    } elseif ($subtotal_compra >= 100 && $subtotal_compra <= 500) {
        return $subtotal_compra * 0.05;
    } elseif ($subtotal_compra > 500 && $subtotal_compra <= 1000) {
        return $subtotal_compra * 0.10;
    } else {
        return $subtotal_compra * 0.15;
    }
}

// Función para aplicar el impuesto al subtotal
function aplicar_impuesto($subtotal_compra) {
    return $subtotal_compra * 0.07;
}

// Función para calcular el total a pagar
function calcular_total($subtotal, $descuento, $impuesto) {
    return $subtotal - $descuento + $impuesto;
}
?>
