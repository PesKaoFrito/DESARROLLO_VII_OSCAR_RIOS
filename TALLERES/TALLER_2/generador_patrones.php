<?php
    for($i = 0; $i < 5; $i++) {
        echo str_repeat("*", $i + 1) . "<br>";
    }
    $num=0;
    while($num<20){
        if ($num%2==0) {
            $num++;
            continue;
        }
        echo $num."<br>";
        $num++;
    }
    $contador = 10;
    do {
        if ($contador == 5) {
            $contador--;
            continue;
        }
        echo $contador . "<br>";
        $contador--;
    } while ($contador > 0);
?>