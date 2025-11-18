<?php

class ErrorLogger {
    private $logFile;
    private static $instance;

    private function __construct($logFile = 'logs/error.log') {
        $this->logFile = $logFile;
        
        // Crear directorio logs si no existe
        $logDir = dirname($logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function logError($mensaje, $tipo = 'ERROR', $archivo = '', $linea = '') {
        $fecha = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
        
        $logMessage = sprintf(
            "[%s] [%s] [%s] %s%s\n",
            $fecha,
            $tipo,
            $ip,
            $mensaje,
            ($archivo && $linea) ? " en $archivo:$linea" : ''
        );

        error_log($logMessage, 3, $this->logFile);
    }

    public function logDatabaseError($mensaje, $query = '', $params = []) {
        $logMessage = $mensaje;
        if ($query) {
            $logMessage .= "\nQuery: " . $query;
        }
        if (!empty($params)) {
            $logMessage .= "\nParámetros: " . print_r($params, true);
        }
        
        $this->logError($logMessage, 'DATABASE_ERROR');
    }
}