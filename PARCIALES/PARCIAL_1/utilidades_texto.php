<?php
// Función para contar palabras en un texto
function contar_palabras($texto) {
    $palabras = explode(' ', $texto);
    return count($palabras);
}

// Función para contar vocales en un texto (sin distinguir mayúsculas y minúsculas)
function contar_vocales($texto) {
    $vocales = preg_match_all('/[aeiouáéíóú]/i', $texto);
    return $vocales;
}

// Función para invertir el orden de las palabras en un texto
function invertir_palabras($texto) {
    $palabras = explode(' ', $texto);
    $palabras_invertidas = array_reverse($palabras);
    return implode(' ', $palabras_invertidas);
}
?>
