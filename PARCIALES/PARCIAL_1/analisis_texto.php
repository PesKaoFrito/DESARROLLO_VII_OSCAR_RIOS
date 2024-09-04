<?php
// analisis_texto.php

// Incluimos el archivo utilidades_texto.php
require_once 'utilidades_texto.php';

// Definimos un array con 3 frases diferentes
$frases = [
    "Hola, ¿cómo estás?",
    "El sol brilla en el cielo azul",
    "Programar es divertido"
];

// Procesamos cada frase y mostramos los resultados en una tabla HTML
echo '<table border="1">';
echo '<tr><th>Frase</th><th>Palabras</th><th>Vocales</th><th>Invertida</th></tr>';

foreach ($frases as $frase) {
    echo '<tr>';
    echo '<td>' . $frase . '</td>';
    echo '<td>' . contar_palabras($frase) . '</td>';
    echo '<td>' . contar_vocales($frase) . '</td>';
    echo '<td>' . invertir_palabras($frase) . '</td>';
    echo '</tr>';
}

echo '</table>';
?>
