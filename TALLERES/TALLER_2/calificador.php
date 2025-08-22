<?php
    $calificacion=85;
    echo "<h2>Condicionales con switch</h2>";
    //Cambia el switch por un if-else
    if ($calificacion >= 90 && $calificacion <= 100) {
        echo "Tu calificación es A.";
        $letra = "A";
    } elseif ($calificacion >= 80 && $calificacion < 90) {
        echo "Tu calificación es B.";
        $letra = "B";
    } elseif ($calificacion >= 70 && $calificacion < 80) {
        echo "Tu calificación es C.";
        $letra = "C";
    } elseif ($calificacion >= 60 && $calificacion < 70) {
        echo "Tu calificación es D.";
        $letra = "D";
    } else {
        echo "Tu calificación es F.";
        $letra = "F";
    }
    $isAprobado = ($calificacion >= 60) ? "Aprobado" : "Reprobado";
    switch ($letra) {
        case "A":
            echo "<br>Excelente trabajo.";
            break;
        case "B":
            echo "<br>Buen trabajo.";
            break;
        case "C":
            echo "<br>Trabajo Aceptable.";
            break;
        case "D":
            echo "<br>Necesitas mejorar.";
            break;
        case "F":
            echo "<br>Debes esforzarte más.";
            break;
        default:
            echo "<br>Calificación no válida.";
    }
?>