<?php
class Producto {
    public $id;
    public $nombre;
    public $categoria;
    public $precio;
    public $cantidad;
    public $fecha_registro;

    // Constructor para crear un objeto Producto a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->nombre = $data['nombre'];
        $this->categoria = $data['categoria'];
        $this->precio = $data['precio'];
        $this->cantidad = $data['cantidad'];
        $this->fecha_registro = $data['fecha_registro'];
    }
}
