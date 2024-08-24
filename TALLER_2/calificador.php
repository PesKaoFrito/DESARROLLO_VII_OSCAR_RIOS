<?php
echo "<h2>Calificador</h2>";
//Declarar variable calificación

$calificacion=85;
if ($calificacion>=90 && $calificacion <=100) {
    $letra='A';
} else if ($calificacion>=80 && $calificacion<=89) {
    $letra='B';
}  else if ($calificacion>=70 && $calificacion<=79){
    $letra='C';
}  else if ($calificacion>=60 && $calificacion<=69){
    $letra='D';
}  else if ($calificacion>=0 && $calificacion<=59) {
    $letra='F';
}  else {
    $letra='Error, Introduce un numero válido';
}
echo "Tu calificación es $letra.<br>";
$estado=($letra === 'F')? 'Reprobado' : 'Aprobado';

echo "Estado: $estado.<br>";

// Usa un switch para imprimir un mensaje adicional basado en la letra de la calificación
switch ($letra) {
    case 'A':
        echo "Excelente trabajo.<br>";
        break;
    case 'B':
        echo "Buen trabajo.<br>";
        break;
    case 'C':
        echo "Trabajo aceptable.<br>";
        break;
    case 'D':
        echo "Necesitas mejorar.<br>";
        break;
    case 'F':
        echo "Debes esforzarte más.<br>";
        break;
    default:
        echo "Calificación no válida.<br>";
        break;
}
?>