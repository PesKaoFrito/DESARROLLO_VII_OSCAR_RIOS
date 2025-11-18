<?php
require_once 'config.php';

class Libros {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function crear($titulo, $autor, $isbn, $anio, $cantidad) {
        try {
            $query = "INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad_disponible) 
                     VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssis", $titulo, $autor, $isbn, $anio, $cantidad);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear el libro: " . $stmt->error);
            }
            
            return $stmt->insert_id;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function listar($pagina = 1, $porPagina = 10) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            // Obtener total de registros
            $totalQuery = "SELECT COUNT(*) as total FROM libros";
            $totalResult = $this->db->getConnection()->query($totalQuery);
            $total = $totalResult->fetch_assoc()['total'];
            
            // Obtener libros paginados
            $query = "SELECT * FROM libros LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $porPagina, $offset);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al listar libros: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $libros = $result->fetch_all(MYSQLI_ASSOC);
            
            return [
                'libros' => $libros,
                'total' => $total,
                'paginas' => ceil($total / $porPagina)
            ];
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function buscar($termino) {
        try {
            $termino = "%$termino%";
            $query = "SELECT * FROM libros 
                     WHERE titulo LIKE ? 
                     OR autor LIKE ? 
                     OR isbn LIKE ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sss", $termino, $termino, $termino);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al buscar libros: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function actualizar($id, $titulo, $autor, $isbn, $anio, $cantidad) {
        try {
            $query = "UPDATE libros 
                     SET titulo = ?, autor = ?, isbn = ?, 
                         anio_publicacion = ?, cantidad_disponible = ? 
                     WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssiii", $titulo, $autor, $isbn, $anio, $cantidad, $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar libro: " . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function eliminar($id) {
        try {
            $query = "DELETE FROM libros WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar libro: " . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function obtenerPorId($id) {
        try {
            $query = "SELECT * FROM libros WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al obtener libro: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_assoc();
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'LIBRO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
}