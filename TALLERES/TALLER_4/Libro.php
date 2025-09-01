<?php
require_once 'Prestable.php';
class Libro implements Prestable {
    private $titulo;
    private $autor;
    private $anioPublicacion;

    public function __construct($titulo, $autor, $anioPublicacion) {
        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setAnioPublicacion($anioPublicacion);
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = trim($titulo);
    }

    public function getAutor() {
        return $this->autor;
    }

    public function setAutor($autor) {
        $this->autor = trim($autor);
    }

    public function getAnioPublicacion() {
        return $this->anioPublicacion;
    }

    public function setAnioPublicacion($anio) {
        $this->anioPublicacion = intval($anio);
    }

    public function obtenerInformacion() {
        return "<br>'{$this->getTitulo()}' por {$this->getAutor()}, publicado en {$this->getAnioPublicacion()}";
    }
    private $disponible = true;

    public function prestar() {
        if ($this->disponible) {
            $this->disponible = false;
            return true;
        }
        return false;
    }

    public function devolver() {
        $this->disponible = true;
    }

    public function estaDisponible() {
        return $this->disponible;
    }
}

// Ejemplo de uso
$miLibro = new Libro("  El Quijote  ", "Miguel de Cervantes", "1605");
echo $miLibro->obtenerInformacion();
echo "<br>Título: " . $miLibro->getTitulo();
$libro = new Libro("Rayuela", "Julio Cortázar", 1963);
echo $libro->obtenerInformacion() . "\n";
echo "<br>Disponible: " . ($libro->estaDisponible() ? "Sí" : "No") . "\n";
$libro->prestar();
echo "<br>Disponible después de prestar: " . ($libro->estaDisponible() ? "Sí" : "No") . "\n";
$libro->devolver();
echo "<br>Disponible después de devolver: " . ($libro->estaDisponible() ? "Sí" : "No") . "\n";

$biblioteca = new Biblioteca();

$libro1 = new Libro("El principito", "Antoine de Saint-Exupéry", 1943);
$libro2 = new LibroDigital("Dune", "Frank Herbert", 1965, "EPUB", 3.2);

$biblioteca->agregarLibro($libro1);
$biblioteca->agregarLibro($libro2);

echo "Listado inicial de libros:\n";
$biblioteca->listarLibros();

echo "Prestando 'El principito'...\n";
$biblioteca->prestarLibro("El principito");

echo "Listado después de prestar:\n";
$biblioteca->listarLibros();

echo "Devolviendo 'El principito'...\n";
$biblioteca->devolverLibro("El principito");

echo "Listado final:\n";
$biblioteca->listarLibros();