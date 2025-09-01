<?php
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