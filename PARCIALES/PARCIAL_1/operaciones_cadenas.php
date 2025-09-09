<?php
    function contar_palabras_repetidas($texto){
        $palabras1= explode(" ", $texto);
        $palabras2= $palabras1;
        print_r($palabras2);
        $i1=0;
        $i2=1;
        $palabrasRep=[];
        while (isset($palabras1[$i1])) {
            $palabraActual=strtolower($palabras1[$i1]);
            $cont=1;
            while (isset($palabras2[$i2])) {
                $palabraComp=strtolower($palabras2[$i2]);
                if ($palabraActual==$palabraComp){
                    $cont++;
                }
                $i2++;
                echo $palabraActual."\n";
                $palabrasRep=[$palabraActual=>$cont];
            }
            ++$i1;
        }
        return $palabrasRep;
    }


    function capitalizar_palabras($texto){
        $palabras= explode(" ", $texto);
        $nuevasPalabras=[];
        foreach ($palabras as $palabra){
            $largoPalabra=strlen($palabra);
            $primeraLetra=strtoupper(substr($palabra,0,1));
            $palabra=$primeraLetra. substr($palabra,1,$largoPalabra-1);
            $nuevasPalabras[]=$palabra;
        }
        $nuevoTexto=implode(" ",$nuevasPalabras);
        return $nuevoTexto;
    }
    echo "Hola\n";
    print_r(contar_palabras_repetidas("Tres menos tres más tres es tres"));
    echo capitalizar_palabras("la primera vez que te vi me sorprendi");
?>