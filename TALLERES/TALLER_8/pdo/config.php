<?php
require_once '../error_log.php';

class Database {
    private static $instance = null;
    private $pdo;
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    private function __construct() {
        $host = 'localhost';
        $db = 'biblioteca_db';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
        
        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $this->pdo = new PDO($dsn, $user, $pass, $this->options);
        } catch (PDOException $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'PDO_ERROR', __FILE__, __LINE__);
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function executeQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logDatabaseError($e->getMessage(), $query, $params);
            throw $e;
        }
    }
    
    public function fetchAll($query, $params = []) {
        return $this->executeQuery($query, $params)->fetchAll();
    }
    
    public function fetchOne($query, $params = []) {
        return $this->executeQuery($query, $params)->fetch();
    }
    
    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ":$field";
        }, $fields);
        
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
        
        $this->executeQuery($query, $data);
        return $this->pdo->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(function($field) {
            return "$field = :$field";
        }, array_keys($data));
        
        $query = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            implode(', ', $fields),
            $where
        );
        
        return $this->executeQuery($query, array_merge($data, $whereParams))->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $query = "DELETE FROM $table WHERE $where";
        return $this->executeQuery($query, $params)->rowCount();
    }
}