<?php

class DataStorage {
    private $archivo_json;

    public function __construct($archivo = 'data/registros.json') {
        $this->archivo_json = $archivo;
        
        // Crear directorio si no existe
        $dir = dirname($archivo);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Crear archivo si no existe
        if (!file_exists($archivo)) {
            file_put_contents($archivo, json_encode([]));
        }
    }

    public function guardarRegistro($datos) {
        $registros = $this->obtenerRegistros();
        $datos['id'] = uniqid();
        $datos['fecha_registro'] = date('Y-m-d H:i:s');
        $registros[] = $datos;
        
        file_put_contents($this->archivo_json, json_encode($registros, JSON_PRETTY_PRINT));
        return $datos['id'];
    }

    public function obtenerRegistros() {
        $contenido = file_get_contents($this->archivo_json);
        return json_decode($contenido, true) ?? [];
    }
}