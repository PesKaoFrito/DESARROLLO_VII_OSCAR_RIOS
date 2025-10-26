<?php
require_once 'config.php';

class Usuarios {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function crear($nombre, $email, $password) {
        try {
            // Verificar si el email ya existe
            if ($this->emailExiste($email)) {
                throw new Exception("El email ya está registrado");
            }
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sss", $nombre, $email, $password_hash);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear usuario: " . $stmt->error);
            }
            
            return $stmt->insert_id;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    private function emailExiste($email) {
        $query = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function listar($pagina = 1, $porPagina = 10) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            $totalQuery = "SELECT COUNT(*) as total FROM usuarios";
            $totalResult = $this->db->getConnection()->query($totalQuery);
            $total = $totalResult->fetch_assoc()['total'];
            
            $query = "SELECT id, nombre, email, created_at FROM usuarios LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $porPagina, $offset);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al listar usuarios: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            return [
                'usuarios' => $result->fetch_all(MYSQLI_ASSOC),
                'total' => $total,
                'paginas' => ceil($total / $porPagina)
            ];
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function buscar($termino) {
        try {
            $termino = "%$termino%";
            $query = "SELECT id, nombre, email, created_at 
                     FROM usuarios 
                     WHERE nombre LIKE ? OR email LIKE ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $termino, $termino);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al buscar usuarios: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function actualizar($id, $nombre, $email, $password = null) {
        try {
            if ($password) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("sssi", $nombre, $email, $password_hash, $id);
            } else {
                $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("ssi", $nombre, $email, $id);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar usuario: " . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function eliminar($id) {
        try {
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar usuario: " . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
    
    public function autenticar($email, $password) {
        try {
            $query = "SELECT id, password FROM usuarios WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $email);
            
            if (!$stmt->execute()) {
                throw new Exception("Error en la autenticación");
            }
            
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                return $usuario['id'];
            }
            
            return false;
            
        } catch (Exception $e) {
            $logger = ErrorLogger::getInstance();
            $logger->logError($e->getMessage(), 'USUARIO_ERROR', __FILE__, __LINE__);
            throw $e;
        }
    }
}