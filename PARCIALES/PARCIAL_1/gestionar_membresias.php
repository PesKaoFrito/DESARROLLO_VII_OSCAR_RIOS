<?php
    include('funciones_gimnasio.php');
    $membresias= ['basica'=> 80,
    'premium'=> 120,
    'vip'=> 180, 
    'familiar'=> 250,
    'corporativa'=> 300];
    $miembros= ['Juan Perez'=> ['tipo'=>'premium', 'antiguedad' => 15],
    'Ana García'=> ['tipo'=>'basica', 'antiguedad' => 2],
    'Carlos López'=> ['tipo'=>'vip', 'antiguedad' => 30],
    'María Rodríguez'=> ['tipo'=>'familiar', 'antiguedad' => 8],
    'Luis Martínez'=> ['tipo'=>'corporativa', 'antiguedad' => 18],]
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gimnasio Parcial 1</title>
</head>
<body>
    <table>
        <tr><th>Miembros del Gimnasio</th></tr>
        <tr><th>Nombre</th><th>Precio</th><th>Cantidad</th></tr>
        <?php
            foreach ($miembros as $miembro=>$membresia){
            $descuentoMonto=(calcular_promocion($membresia['antiguedad'])*100)."% - ". calcular_promocion($membresia['antiguedad'])* $membresias["{$membresia['tipo']}"] ;
        ?>
        <tr><td><?=$miembro?></td><td><?=$membresia['tipo']?></td><td><?=$membresia['antiguedad']?></td>
        <td><?=$membresias["{$membresia['tipo']}"]?></td>
        <td><?=$descuentoMonto ?></td> <td> </td></tr>
        <?php }?>
    </table>
</body>
</html>