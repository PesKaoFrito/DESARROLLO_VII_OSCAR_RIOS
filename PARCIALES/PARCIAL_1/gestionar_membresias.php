<?php
    include 'funciones_gimnasio.php';
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
        <tr><th>Miembros</th><th>Membresía</th><th>Antigüedad</th><th>Cuota base</th><th>Descuento Aplicado</th><th>Seguro médico</th><th>Cuota final</th></tr>
        <?php
            foreach ($miembros as $miembro=>$membresia){
            $cuotaBase= $membresias["{$membresia['tipo']}"];
            $descuentoMonto=(calcular_promocion($membresia['antiguedad'])*100)."% - ". calcular_promocion($membresia['antiguedad'])* $membresias["{$membresia['tipo']}"] ;
            $seguro=seguro_medico($cuotaBase);
            $cuotaFinal=calcular_cuota_final($cuotaBase, calcular_promocion($membresia['antiguedad']),$seguro);
        ?>
        <tr><td><?=$miembro?></td><td><?=$membresia['tipo']?></td><td><?=$membresia['antiguedad']?></td>
        <td><?=$membresias["{$membresia['tipo']}"]?></td>
        <td><?=$descuentoMonto ?></td> <td><?=$seguro?></td> <td><?=$cuotaFinal?></td></tr>
        <?php }?>
    </table>
</body>
</html>