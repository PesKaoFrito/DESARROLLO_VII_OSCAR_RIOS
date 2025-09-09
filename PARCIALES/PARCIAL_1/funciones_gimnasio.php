<?php
    function calcular_promocion($antiguedad_meses){
        $descuento=0;
        if ($antiguedad_meses>=3 && $antiguedad_meses<=12){
            $descuento=8;
        }
        else if($antiguedad_meses>=13 && $antiguedad_meses<=24){
            $descuento=12;
        }
        else if($antiguedad_meses>=24){
            $descuento=20;
        }
        return $descuento;
    }
    function seguro_medico($cuota_base){
        return $cuota_base*0.05;
    }
    function calcular_cuota_final($cuota_base, $descuento_porcentaje, $seguro_medico){
        return $cuota_base-$cuota_base-$descuento_porcentaje+$seguro_medico;
    }
?>