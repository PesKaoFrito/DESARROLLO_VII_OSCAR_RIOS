<?php
require_once '../error_log.php';

class Database {
    private static $instance = null;
    private $mysqli;
    
    private function __construct() {
        $host = 'localhost';
        $usuario = 'root';
        $password = '';
        $base_datos = 'biblioteca_db';
        
        try {
            $this->mysqli = new mysqli($host, $usuario, $password, $base_datos);
            
            if ($this->mysqli->connect_error) {
                throw new Exception("Error de conexión: " . $this->mysqli->connect_error);
            }
            
            $this->mysqli->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'MYSQLI_ERROR', __FILE__, __LINE__);
            die("Error de conexión. Por favor, intente más tarde.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->mysqli;
    }
    
    public function prepare($query) {
        $stmt = $this->mysqli->prepare($query);
        if ($stmt === false) {
            $logger = ErrorLogger::getInstance();
            $logger->logDatabaseError(
                "Error al preparar consulta: " . $this->mysqli->error,
                $query
            );
            throw new Exception("Error al preparar la consulta");
        }
        return $stmt;
    }
    
    public function beginTransaction() {
        $this->mysqli->begin_transaction();
    }
    
    public function commit() {
        $this->mysqli->commit();
    }
    
    public function rollback() {
        $this->mysqli->rollback();
    }
}