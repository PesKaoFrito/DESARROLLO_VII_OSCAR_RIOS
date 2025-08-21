<?php
    $nombre = "María";
    $edad = 30;
    $correo = "maria@example.com";
    $telefono = "123456789";
    define("OCUPACION", "Ingeniera");
    echo "<h1>Perfil de Usuario</h1>";
    print "<p>Nombre: $nombre</p>";
    print "<p>Edad: $edad</p>";
    printf ("<p>Correo: $correo</p>");
    print "<p>Teléfono: $telefono</p>";
    print "<p>Ocupación: " . OCUPACION . "</p>";
?>