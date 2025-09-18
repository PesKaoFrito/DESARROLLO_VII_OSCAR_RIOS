<?php
    // Inclusión centralizada en el orden correcto para evitar redeclare y dependencias faltantes
    require_once 'Empleado.php';
    require_once 'Evaluable.php';
    require_once 'Desarrollador.php';
    require_once 'Gerente.php';
    require_once 'Empresa.php';

    $empresa=new Empresa();

    $dev1=new Desarrollador("Alice", "D001", 60000, "PHP", 4);
    $dev2=new Desarrollador("Bob", "D002", 70000, "JavaScript", 6);
    $gerente1=new Gerente("Charlie", "G001", 90000, "ventas");
    $gerente2=new Gerente("Diana", "G002", 85000, "marketing");

    $empresa->agregarEmpleado($dev1);
    $empresa->agregarEmpleado($dev2);
    $empresa->agregarEmpleado($gerente1);
    $empresa->agregarEmpleado($gerente2);

    echo "<h2>Lista de Empleados:</h2>";
    $empresa->listarEmpleados();

    echo "<h2>Nómina Total:</h2>";
    echo "La nómina total de la empresa es: ".$empresa->calcularNominaTotal();