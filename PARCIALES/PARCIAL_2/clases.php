<?php

interface Detalle {
    public function obtenerDetallesEspecificos(): string;
}

class Entrada {
    public $id;
    public $fecha_creacion;
    public $tipo;
    public $titulo;
    public $descripcion;
    public $titulo1;
    public $descripcion1;
    public $titulo2;
    public $descripcion2;
    public $titulo3;
    public $descripcion3;


    public function __construct($datos = []) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    
    }
    
    
class GestorBlog {
    private $entradas = [];

    public function cargarEntradas() {
        if (file_exists('blog.json')) {
            $json = file_get_contents('blog.json');
            $data = json_decode($json, true);
            foreach ($data as $entradaData) {
                $this->entradas[] = new Entrada($entradaData);
            }
        }
    }

    public function guardarEntradas() {
        $data = array_map(function($entrada) {
            return get_object_vars($entrada);
        }, $this->entradas);
        file_put_contents('blog.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerEntradas() {
        return $this->entradas;
    }

    public function agregarEntrada(Entrada $entrada)  {
        $entrada=["id"=> $id, "fecha_creacion"=> $fecha_creacion, "tipo"=> $tipo, "titulo"=>$titulo, "descripción"=>$descripcion ];
        $entradas[]=$entrada;
        guardarEntradas();
    }
    public function editarEntrada(Entrada $entrada) {
        $entrada=["id"=> $id, "fecha_creacion"=> $fecha_creacion, "tipo"=> $tipo, "titulo"=>$titulo, "descripción"=>$descripcion ];
        $entradas[]=array_replace($entrada);
        guardarEntradas();
    }
    
}


class EntradaUnaColumna extends Entrada {
    public function obtenerDetallesEspecificos(): string {
        return "Entrada de una columna: " . $this->titulo;
    }
}

class EntradaDosColumnas extends Entrada {
    public function obtenerDetallesEspecificos(): string {
        return "Entrada de dos columnas: " . $this->titulo1 . " | " . $this->titulo2;
    }
}

class EntradaTresColumnas extends Entrada {
    public function obtenerDetallesEspecificos(): string {
        return "Entrada de tres columnas: " . $this->titulo1 . " | " . $this->titulo2 . " | " . $this->titulo3;
    }
}

// Definición de la interfaz Detalle


?>