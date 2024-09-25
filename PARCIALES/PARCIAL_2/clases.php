<?php
// Archivo: clases.php

class Tarea implements Detalle {
    public $id;
    public $titulo;
    public $descripcion;
    public $estado;
    public $prioridad;
    public $fechaCreacion;
    public $tipo;

    public function __construct($datos) {
        foreach ($datos as $key => $value) {
            $this->$key = $value;
        }
    }

    // Implementar estos getters
    public function getEstado() {
        return $this->estado;
    }

    public function getPrioridad() {
        return $this->prioridad;
    }
}

class GestorTareas {
    private $tareas = [];

    public function cargarTareas() {
        $json = file_get_contents('tareas.json');
        $data = json_decode($json, true);
        foreach ($data as $tareaData) {
            switch ($tareaData['tipo']) {
                case 'Desarrollo':
                    $tarea = new TareaDesarrollo($tareaData);
                    break;
                case 'Diseño':
                    $tarea = new TareaDiseno($tareaData);
                    break;
                case 'Testing':
                    $tarea = new TareaTesting($tareaData);
                    break;
                default:
                    $tarea = new Tarea($tareaData);
                    break;
            }
            $this->tareas[] = $tarea;
        }
        
        return $this->tareas;
    }

    public function agregarTarea($tarea) {
        $this->tareas[] = $tarea;
    }

    public function eliminarTarea($id) {
        foreach ($this->tareas as $key => $tarea) {
            if ($tarea->id == $id) {
                unset($this->tareas[$key]);
                $this->tareas = array_values($this->tareas); // Reindexar el array
                return true;
            }
        }
        return false;
    }

    public function actualizarTarea($tareaActualizada) {
        foreach ($this->tareas as $key => $tarea) {
            if ($tarea->id == $tareaActualizada->id) {
                $this->tareas[$key] = $tareaActualizada;
                return true;
            }
        }
        return false;
    }

    public function actualizarEstadoTarea($id, $nuevoEstado) {
        foreach ($this->tareas as $tarea) {
            if ($tarea->id == $id) {
                $tarea->estado = $nuevoEstado;
                return true;
            }
        }
        return false;
    }

    public function buscarTareasPorEstado($estado) {
        $tareasFiltradas = [];
        foreach ($this->tareas as $tarea) {
            if ($tarea->estado == $estado) {
                $tareasFiltradas[] = $tarea;
            }
        }
        return $tareasFiltradas;
    }

    public function listarTareas($filtroEstado = '') {
        if ($filtroEstado) {
            return $this->buscarTareasPorEstado($filtroEstado);
        }
        return $this->tareas;
    }
}

interface Detalle {
    public function getEstado();
    public function getPrioridad();
}

class TareaDesarrollo extends Tarea {
    public function __construct($datos) {
        parent::__construct($datos);
        $this->tipo = 'Desarrollo';
    }
}

class TareaDiseno extends Tarea {
    public function __construct($datos) {
        parent::__construct($datos);
        $this->tipo = 'Diseño';
    }
}

class TareaTesting extends Tarea {
    public function __construct($datos) {
        parent::__construct($datos);
        $this->tipo = 'Testing';
    }
}