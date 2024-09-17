<?php
// Patrón de triángulo rectángulo con asteriscos (*)
echo "Patrón de triángulo rectángulo:<br>";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "<br>";
}
// Secuencia de números impares del 1 al 20 (bucle while)
echo "<br>Secuencia de números impares:<br>";
$num = 1;
while ($num <= 20) {
    if ($num % 2 != 0) {
        echo $num . " ";
    }
    $num++;
}
// Contador regresivo con salto del número 5 (bucle do-while)
echo "<br>Contador regresivo con salto del 5:<br>";
$num = 10;
do {
    if ($num != 5) {
        echo $num . " ";
    }
    $num--;
} while ($num >= 1);
?>