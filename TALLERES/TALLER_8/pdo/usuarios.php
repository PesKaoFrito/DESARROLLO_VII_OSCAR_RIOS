<?php
require_once 'config.php';

class Usuarios {
    private $db;
    private $table = 'usuarios';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function crear($datos) {
        try {
            // Validar email único
            if ($this->emailExiste($datos['email'])) {
                throw new Exception("El email ya está registrado");
            }
            
            // Hash de la contraseña
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            return $this->db->insert($this->table, [
                'nombre' => $datos['nombre'],
                'email' => $datos['email'],
                'password' => $datos['password']
            ]);
            
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Duplicate entry
                throw new Exception("El email ya está registrado");
            }
            throw $e;
        }
    }
    
    private function emailExiste($email) {
        $resultado = $this->db->fetchOne(
            "SELECT id FROM {$this->table} WHERE email = :email",
            [':email' => $email]
        );
        return $resultado !== false;
    }
    
    public function listar($pagina = 1, $porPagina = 10, $busqueda = null) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            $params = [];
            $whereClause = "";
            
            if ($busqueda) {
                $whereClause = "WHERE nombre LIKE :busqueda OR email LIKE :busqueda";
                $params[':busqueda'] = "%$busqueda%";
            }
            
            // Obtener total de registros
            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM {$this->table} $whereClause",
                $params
            )['total'];
            
            // Obtener usuarios paginados
            $query = "SELECT id, nombre, email, created_at 
                     FROM {$this->table} 
                     $whereClause 
                     ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset";
            
            $params[':limit'] = $porPagina;
            $params[':offset'] = $offset;
            
            return [
                'usuarios' => $this->db->fetchAll($query, $params),
                'total' => $total,
                'paginas' => ceil($total / $porPagina),
                'pagina_actual' => $pagina
            ];
            
        } catch (Exception $e) {
            throw new Exception("Error al listar usuarios: " . $e->getMessage());
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $actualizaciones = [];
            $params = [':id' => $id];
            
            // Construir actualización dinámica
            if (isset($datos['nombre'])) {
                $actualizaciones[] = 'nombre = :nombre';
                $params[':nombre'] = $datos['nombre'];
            }
            
            if (isset($datos['email'])) {
                // Verificar si el nuevo email ya existe para otro usuario
                $emailActual = $this->db->fetchOne(
                    "SELECT email FROM {$this->table} WHERE id = :id",
                    [':id' => $id]
                );
                
                if ($emailActual['email'] !== $datos['email'] && $this->emailExiste($datos['email'])) {
                    throw new Exception("El email ya está en uso por otro usuario");
                }
                
                $actualizaciones[] = 'email = :email';
                $params[':email'] = $datos['email'];
            }
            
            if (isset($datos['password'])) {
                $actualizaciones[] = 'password = :password';
                $params[':password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            }
            
            if (empty($actualizaciones)) {
                throw new Exception("No hay datos para actualizar");
            }
            
            $query = "UPDATE {$this->table} 
                     SET " . implode(', ', $actualizaciones) . " 
                     WHERE id = :id";
            
            return $this->db->executeQuery($query, $params)->rowCount() > 0;
            
        } catch (Exception $e) {
            throw new Exception("Error al actualizar usuario: " . $e->getMessage());
        }
    }
    
    public function eliminar($id) {
        try {
            // Verificar préstamos activos
            $prestamosActivos = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM prestamos 
                 WHERE usuario_id = :id AND estado = 'activo'",
                [':id' => $id]
            );
            
            if ($prestamosActivos['total'] > 0) {
                throw new Exception("No se puede eliminar el usuario porque tiene préstamos activos");
            }
            
            return $this->db->delete($this->table, 'id = :id', [':id' => $id]);
            
        } catch (Exception $e) {
            throw new Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }
    
    public function autenticar($email, $password) {
        try {
            $usuario = $this->db->fetchOne(
                "SELECT id, password FROM {$this->table} WHERE email = :email",
                [':email' => $email]
            );
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                return $usuario['id'];
            }
            
            return false;
            
        } catch (Exception $e) {
            throw new Exception("Error en la autenticación: " . $e->getMessage());
        }
    }
    
    public function obtenerEstadisticas($usuario_id) {
        try {
            return $this->db->fetchOne(
                "SELECT 
                    (SELECT COUNT(*) FROM prestamos 
                     WHERE usuario_id = :id AND estado = 'activo') as prestamos_activos,
                    (SELECT COUNT(*) FROM prestamos 
                     WHERE usuario_id = :id AND estado = 'devuelto') as prestamos_completados,
                    (SELECT COUNT(*) FROM prestamos 
                     WHERE usuario_id = :id AND estado = 'vencido') as prestamos_vencidos
                 FROM {$this->table} 
                 WHERE id = :id",
                [':id' => $usuario_id]
            );
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
}