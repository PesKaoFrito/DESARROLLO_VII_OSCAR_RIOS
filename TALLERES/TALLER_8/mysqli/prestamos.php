<?php
require_once 'config.php';

class Prestamos {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function registrarPrestamo($usuario_id, $libro_id) {
        try {
            $this->db->beginTransaction();
            
            // Verificar disponibilidad del libro
            $query = "SELECT cantidad_disponible FROM libros WHERE id = ? FOR UPDATE";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $libro_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $libro = $result->fetch_assoc();
            
            if (!$libro || $libro['cantidad_disponible'] <= 0) {
                throw new Exception("Libro no disponible");
            }
            
            // Registrar préstamo
            $query = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $usuario_id, $libro_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al registrar préstamo");
            }
            
            // Actualizar cantidad disponible
            $query = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $libro_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar disponibilidad");
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'PRESTAMO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function registrarDevolucion($prestamo_id) {
        try {
            $this->db->beginTransaction();
            
            // Obtener información del préstamo
            $query = "SELECT libro_id FROM prestamos WHERE id = ? AND estado = 'activo'";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $prestamo_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $prestamo = $result->fetch_assoc();
            
            if (!$prestamo) {
                throw new Exception("Préstamo no encontrado o ya devuelto");
            }
            
            // Registrar devolución
            $query = "UPDATE prestamos 
                     SET estado = 'devuelto', 
                         fecha_devolucion = NOW() 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $prestamo_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al registrar devolución");
            }
            
            // Actualizar cantidad disponible
            $query = "UPDATE libros 
                     SET cantidad_disponible = cantidad_disponible + 1 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $prestamo['libro_id']);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar disponibilidad");
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'PRESTAMO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function listarPrestamosActivos($pagina = 1, $porPagina = 10) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            $query = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo
                     FROM prestamos p
                     JOIN usuarios u ON p.usuario_id = u.id
                     JOIN libros l ON p.libro_id = l.id
                     WHERE p.estado = 'activo'
                     LIMIT ? OFFSET ?";
                     
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $porPagina, $offset);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al listar préstamos");
            }
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'PRESTAMO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function obtenerHistorialUsuario($usuario_id) {
        try {
            $query = "SELECT p.*, l.titulo as libro_titulo
                     FROM prestamos p
                     JOIN libros l ON p.libro_id = l.id
                     WHERE p.usuario_id = ?
                     ORDER BY p.fecha_prestamo DESC";
                     
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $usuario_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al obtener historial");
            }
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'PRESTAMO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
}