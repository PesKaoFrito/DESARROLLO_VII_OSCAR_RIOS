<?php
require_once 'Libro.php';
require_once 'LibroDigital.php';

class Biblioteca {
    private $libros = [];

    public function agregarLibro(Prestable $libro) {
        $this->libros[] = $libro;
    }

    public function listarLibros() {
        foreach ($this->libros as $libro) {
            echo $libro->obtenerInformacion() . "<br>";
            echo "Disponible: " . ($libro->estaDisponible() ? "Sí" : "No") . "<br><br>";
        }
    }

    public function prestarLibro($titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo && $libro->estaDisponible()) {
                $libro->prestar();
                return true;
            }
        }
        return false;
    }

    public function devolverLibro($titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo && !$libro->estaDisponible()) {
                $libro->devolver();
                return true;
            }
        }
        return false;
    }
}

// Ejemplo de uso
$biblioteca = new Biblioteca();

$libro1 = new Libro("El principito", "Antoine de Saint-Exupéry", 1943);
$libro2 = new LibroDigital("Dune", "Frank Herbert", 1965, "EPUB", 3.2);

$biblioteca->agregarLibro($libro1);
$biblioteca->agregarLibro($libro2);

echo "Listado inicial de libros:<br>";
$biblioteca->listarLibros();

echo "Prestando 'El principito'...<br>";
$biblioteca->prestarLibro("El principito");

echo "Listado después de prestar:<br>";
$biblioteca->listarLibros();

echo "Devolviendo 'El principito'...<br>";
$biblioteca->devolverLibro("El principito");

echo "Listado final:<br>";
$biblioteca->listarLibros();