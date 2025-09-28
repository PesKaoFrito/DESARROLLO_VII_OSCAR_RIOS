<?php
    class Estudiante {
        public $id;
        public $nombre;
        public $edad;
        public $carrera;
        
        public $materias = [];

        public function __construct($id, $nombre, $edad, $carrera, $materias) {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->edad = $edad;
            $this->carrera = $carrera;
            $this->materias = $materias;
        }

        public function agregarMateria($materia, $nota) {
            $this->materias[$materia] = $nota;
        }

        public function obtenerPromedio() {
            $total = array_sum($this->materias);
            $cantidad = count($this->materias);
            return $cantidad > 0 ? $total / $cantidad : 0;
            
        }

        public function obtenerDetallesEstudiante(){
            return [
                "id" => $this->id,
                "nombre" => $this->nombre,
                "edad" => $this->edad,
                "carrera" => $this->carrera,
                "materias" => $this->materias,
                "promedio" => $this->obtenerPromedio()
            ];
        }
    }
    class SistemaGestionEstudiantes {
        private $estudiantes = [];

        public function agregarEstudiante($estudiante) {
            $this->estudiantes[] = $estudiante;
        }

        public function listarEstudiantes() {
            $lista = [];
            foreach ($this->estudiantes as $estudiante) {
                $lista[] = $estudiante->obtenerDetallesEstudiante();
            }
            return $lista;
        }

        public function obtenerEstudiante($id) {
            foreach ($this->estudiantes as $estudiante) {
                if ($estudiante->id === $id) {
                    return $estudiante->obtenerDetallesEstudiante();
                }
            }
            return null;
        }
        function calcularPromedioGeneral() {
            $totalPromedio = 0;
            $cantidadEstudiantes = count($this->estudiantes);
            if ($cantidadEstudiantes === 0) {
                return 0;
            }
            foreach ($this->estudiantes as $estudiante) {
                $totalPromedio += $estudiante->obtenerPromedio();
            }
            return $totalPromedio / $cantidadEstudiantes;
        }

        function obtenerEstudiantesPorCarrera($carrera) {
            $estudiantesCarrera = [];
            foreach ($this->estudiantes as $estudiante) {
                if ($estudiante->carrera === $carrera) {
                    $estudiantesCarrera[] = $estudiante->obtenerDetallesEstudiante();
                }
            }
            return $estudiantesCarrera;
        }

        function obtenerMejorEstudiante() {
            $mejorEstudiante = null;
            $mejorPromedio = -1;
            foreach ($this->estudiantes as $estudiante) {
                $promedio = $estudiante->obtenerPromedio();
                if ($promedio > $mejorPromedio) {
                    $mejorPromedio = $promedio;
                    $mejorEstudiante = $estudiante;
                }
            }
            return $mejorEstudiante ? $mejorEstudiante->obtenerDetallesEstudiante() : null;
        }

        function generarReporteRendimiento() {
            $datos = [];

            // Recolectar sumas, máximos, mínimos y conteos por clave (puede ser índice numérico o nombre)
            foreach ($this->estudiantes as $estudiante) {
                foreach ($estudiante->materias as $clave => $nota) {
                    // usamos la clave tal cual (si es numérica, queda como int; si es string, queda como nombre)
                    if (!isset($datos[$clave])) {
                        $datos[$clave] = [
                            'suma' => 0,
                            'count' => 0,
                            'max' => PHP_INT_MIN,
                            'min' => PHP_INT_MAX
                        ];
                    }
                    $datos[$clave]['suma'] += $nota;
                    $datos[$clave]['count']++;
                    if ($nota > $datos[$clave]['max']) $datos[$clave]['max'] = $nota;
                    if ($nota < $datos[$clave]['min']) $datos[$clave]['min'] = $nota;
                }
            }

            // Construir el reporte final usando nombres legibles para las materias
            $reporte = [];
            foreach ($datos as $clave => $d) {
                // Si la clave es numérica, convertimos a "Materia N" (humano-leyendo)
                $nombreMateria = is_int($clave) ? "Materia " . ($clave + 1) : $clave;
                $promedio = $d['count'] > 0 ? $d['suma'] / $d['count'] : 0;

                $reporte[$nombreMateria] = [
                    'nombre' => $nombreMateria,                 // <-- aquí se muestra el nombre de la materia
                    'promedio' => $promedio,
                    'maxima' => $d['max'] === PHP_INT_MIN ? null : $d['max'],
                    'minima' => $d['min'] === PHP_INT_MAX ? null : $d['min'],
                    'alumnos' => $d['count']
                ];
            }

            return $reporte;
        }

        function graduarEstudiante($id) {
            foreach ($this->estudiantes as $index => $estudiante) {
                if ($estudiante->id === $id) {
                    unset($this->estudiantes[$index]);
                    return true;
                }
            }
            return false;
        }

        function generarRanking(){
            $ranking = $this->estudiantes;
            usort($ranking, function($a, $b) {
                return $b->obtenerPromedio() <=> $a->obtenerPromedio();
            });
            $resultado = [];
            foreach ($ranking as $estudiante) {
                $resultado[] = $estudiante->obtenerDetallesEstudiante();
            }
            return $resultado;
        }
    }
    // Instanciar el sistema de gestión de estudiantes
    $sistema = new SistemaGestionEstudiantes();
    // Crear y agregar estudiantes
    $estudiantesData = [
        ["id" => 1, "nombre" => "Juan", "edad" => 20, "carrera" => "Ingeniería", "materias" => ["Fisica" => 5, "Matematica" => 4, "Quimica" => 3]],
        ["id" => 2, "nombre" => "María", "edad" => 22, "carrera" => "Medicina", "materias" => ["Biologia" => 4, "Quimica" => 5, "Anatomia" => 5]],
        ["id" => 3, "nombre" => "Pedro", "edad" => 21, "carrera" => "Arquitectura", "materias" => ["Dibujo" => 3, "Historia" => 4, "Matematica" => 4]],
        ["id" => 4, "nombre" => "Ana", "edad" => 23, "carrera" => "Ingeniería", "materias" => ["Fisica" => 5, "Matematica" => 5, "Quimica" => 5]],
        ["id" => 5, "nombre" => "Luis", "edad" => 20, "carrera" => "Medicina", "materias" => ["Biologia" => 4, "Quimica" => 4, "Anatomia" => 4]],
        ["id" => 6, "nombre" => "Laura", "edad" => 22, "carrera" => "Arquitectura", "materias" => ["Dibujo" => 3, "Historia" => 3, "Matematica" => 4]],
        ["id" => 7, "nombre" => "Carlos", "edad" => 21, "carrera" => "Ingeniería", "materias" => ["Fisica" => 5, "Matematica" => 4, "Quimica" => 3]],
        ["id" => 8, "nombre" => "Sofía", "edad" => 23, "carrera" => "Medicina", "materias" => ["Biologia" => 4, "Quimica" => 5, "Anatomia" => 5]],
        ["id" => 9, "nombre" => "Andrés", "edad" => 20, "carrera" => "Arquitectura", "materias" => ["Dibujo" => 3, "Historia" => 4, "Matematica" => 4]],
        ["id" => 10, "nombre" => "Valeria", "edad" => 22, "carrera" => "Ingeniería", "materias" => ["Fisica" => 5, "Matematica" => 5, "Quimica" => 5]],
    ];

    foreach ($estudiantesData as $data) {
        $estudiante = new Estudiante(
            $data["id"],
            $data["nombre"],
            $data["edad"],
            $data["carrera"],
            $data["materias"]
        );
        $sistema->agregarEstudiante($estudiante);
    }

    // Preparar datos
    $listaEstudiantes = $sistema->listarEstudiantes();
    $promedioGeneral = $sistema->calcularPromedioGeneral();
    $carrera = "Ingeniería";
    $estudiantesIngenieria = $sistema->obtenerEstudiantesPorCarrera($carrera);
    $mejorEstudiante = $sistema->obtenerMejorEstudiante();
    $reporteRendimiento = $sistema->generarReporteRendimiento();
    $rankingEstudiantes = $sistema->generarRanking();

    // Salida en tablas HTML
    echo '<h2>Lista de Estudiantes</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<thead><tr><th>ID</th><th>Nombre</th><th>Edad</th><th>Carrera</th><th>Promedio</th><th>Materias</th></tr></thead><tbody>';
    foreach ($listaEstudiantes as $est) {
        $materiasHtml = '';
        foreach ($est['materias'] as $mat => $nota) {
            $materiasHtml .= htmlspecialchars($mat) . ': ' . htmlspecialchars((string)$nota) . '<br>';
        }
        echo '<tr>';
        echo '<td>' . htmlspecialchars((string)$est['id']) . '</td>';
        echo '<td>' . htmlspecialchars($est['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars((string)$est['edad']) . '</td>';
        echo '<td>' . htmlspecialchars($est['carrera']) . '</td>';
        echo '<td>' . number_format((float)$est['promedio'], 2) . '</td>';
        echo '<td>' . $materiasHtml . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    echo '<h2>Promedio General de Estudiantes</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<tr><th>Promedio General</th></tr>';
    echo '<tr><td>' . number_format((float)$promedioGeneral, 2) . '</td></tr>';
    echo '</table>';

    echo '<h2>Estudiantes de la carrera de ' . htmlspecialchars($carrera) . '</h2>';
    if (empty($estudiantesIngenieria)) {
        echo '<p>No hay estudiantes en esa carrera.</p>';
    } else {
        echo '<table border="1" cellpadding="8" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Edad</th><th>Promedio</th></tr></thead><tbody>';
        foreach ($estudiantesIngenieria as $est) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars((string)$est['id']) . '</td>';
            echo '<td>' . htmlspecialchars($est['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars((string)$est['edad']) . '</td>';
            echo '<td>' . number_format((float)$est['promedio'], 2) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    echo '<h2>Mejor Estudiante</h2>';
    if ($mejorEstudiante === null) {
        echo '<p>No hay estudiantes registrados.</p>';
    } else {
        echo '<table border="1" cellpadding="8" cellspacing="0">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Carrera</th><th>Promedio</th></tr></thead><tbody>';
        echo '<tr>';
        echo '<td>' . htmlspecialchars((string)$mejorEstudiante['id']) . '</td>';
        echo '<td>' . htmlspecialchars($mejorEstudiante['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($mejorEstudiante['carrera']) . '</td>';
        echo '<td>' . number_format((float)$mejorEstudiante['promedio'], 2) . '</td>';
        echo '</tr>';
        echo '</tbody></table>';
    }

    echo '<h2>Reporte de Rendimiento por Materia</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<thead><tr><th>Materia</th><th>Promedio</th><th>Máxima</th><th>Mínima</th><th>Alumnos</th></tr></thead><tbody>';
    foreach ($reporteRendimiento as $materia => $datos) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($datos['nombre']) . '</td>';
        echo '<td>' . number_format((float)$datos['promedio'], 2) . '</td>';
        echo '<td>' . htmlspecialchars((string)$datos['maxima']) . '</td>';
        echo '<td>' . htmlspecialchars((string)$datos['minima']) . '</td>';
        echo '<td>' . htmlspecialchars((string)$datos['alumnos']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    echo '<h2>Ranking de Estudiantes</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<thead><tr><th>Puesto</th><th>ID</th><th>Nombre</th><th>Carrera</th><th>Promedio</th></tr></thead><tbody>';
    $puesto = 1;
    foreach ($rankingEstudiantes as $est) {
        echo '<tr>';
        echo '<td>' . $puesto++ . '</td>';
        echo '<td>' . htmlspecialchars((string)$est['id']) . '</td>';
        echo '<td>' . htmlspecialchars($est['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($est['carrera']) . '</td>';
        echo '<td>' . number_format((float)$est['promedio'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    // Graduar un estudiante (ejemplo)
    $idEstudianteAGraduar = 1; // Cambiar por el ID del estudiante a graduar
    $sistema->graduarEstudiante($idEstudianteAGraduar);
    // Finalizar el script
    exit();

?>