<?php
    //Definición de Variables
    $nombre_completo="Oscar Alejandro Ríos González";
    $edad=22;
    $correo="oarg2704@gmail.com";
    $telefono="6676-3008";

    //Definición de Constantes
    define("OCUPACION","Estudiante");

    $oracion1= "Hola, me llamo $nombre_completo y tengo $edad años.";
    $oracion2= "Mi correo es ".$correo ."y mi numero de teléfono es ".$telefono .".";

    echo $oracion1 ."<br>";
    print ($oracion2. "<br>");

    printf("En pocas palabras: %s, %d años, %s, %s<br>", $nombre_completo, $edad, $correo, $telefono);

    echo "<br>Información de debugging:<br>";
    var_dump($nombre_completo);
    echo "<br>";
    var_dump($edad);
    echo "<br>";
    var_dump($correo);
    echo "<br>";
    var_dump($telefono);
    echo "<br>";
    var_dump(OCUPACION);
    echo "<br>";



?>