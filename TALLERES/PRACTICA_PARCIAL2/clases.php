<?php
interface Prestable{
    function obtenerDetallesPrestamo(): string;
}

abstract class RecursoBiblioteca implements Prestable{
    public $id;
    public $titulo;
    public $autor;
    public $anioPublicacion;
    public $estado;
    public $fechaAdquisicion;
    public $tipo;

    public function __construct($datos) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

class Libro extends RecursoBiblioteca{
    public string $isbn;
    function obtenerDetallesPrestamo(): string;
}

class Revista extends RecursoBiblioteca{
    public int $numeroEdicion;

}

class DVD extends RecursoBiblioteca{
    public int $duracion;
}

class GestorBiblioteca {
    private $recursos = [];

    public function cargarRecursos() {
        $json = file_get_contents('biblioteca.json');
        $data = json_decode($json, true);
        
        foreach ($data as $recursoData) {
            switch ($recursoData['tipo']) {
                case 'Libro':
                    # code...
                    break;
                
                case 'Revista':
                    # code...
                    break;
                
                case 'DVD':
                    # code...
                    break;

                default:
                    # code...
                    break;
            }
            $recurso = new RecursoBiblioteca($recursoData);
            $this->recursos[] = $recurso;
        }
        
        return $this->recursos;
    }

    // Implementar los demás métodos aquí
}