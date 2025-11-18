<?php
require_once 'config.php';

class Prestamos {
    private $db;
    private $table = 'prestamos';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function registrarPrestamo($usuario_id, $libro_id) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Verificar disponibilidad del libro
            $libro = $this->db->fetchOne(
                "SELECT cantidad_disponible FROM libros WHERE id = :libro_id FOR UPDATE",
                [':libro_id' => $libro_id]
            );
            
            if (!$libro || $libro['cantidad_disponible'] <= 0) {
                throw new Exception("Libro no disponible");
            }
            
            // Verificar si el usuario tiene préstamos vencidos
            $prestamosVencidos = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM prestamos 
                 WHERE usuario_id = :usuario_id 
                 AND estado = 'vencido'",
                [':usuario_id' => $usuario_id]
            );
            
            if ($prestamosVencidos['total'] > 0) {
                throw new Exception("El usuario tiene préstamos vencidos");
            }
            
            // Registrar préstamo
            $resultado = $this->db->insert($this->table, [
                'usuario_id' => $usuario_id,
                'libro_id' => $libro_id,
                'fecha_prestamo' => date('Y-m-d H:i:s'),
                'estado' => 'activo'
            ]);
            
            // Actualizar cantidad disponible
            $this->db->executeQuery(
                "UPDATE libros 
                 SET cantidad_disponible = cantidad_disponible - 1 
                 WHERE id = :libro_id",
                [':libro_id' => $libro_id]
            );
            
            $this->db->getConnection()->commit();
            return $resultado;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
    
    public function registrarDevolucion($prestamo_id) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Obtener información del préstamo
            $prestamo = $this->db->fetchOne(
                "SELECT libro_id, estado FROM prestamos 
                 WHERE id = :prestamo_id",
                [':prestamo_id' => $prestamo_id]
            );
            
            if (!$prestamo) {
                throw new Exception("Préstamo no encontrado");
            }
            
            if ($prestamo['estado'] !== 'activo') {
                throw new Exception("El préstamo ya fue procesado");
            }
            
            // Registrar devolución
            $this->db->update(
                $this->table,
                [
                    'estado' => 'devuelto',
                    'fecha_devolucion' => date('Y-m-d H:i:s')
                ],
                'id = :id',
                [':id' => $prestamo_id]
            );
            
            // Actualizar cantidad disponible
            $this->db->executeQuery(
                "UPDATE libros 
                 SET cantidad_disponible = cantidad_disponible + 1 
                 WHERE id = :libro_id",
                [':libro_id' => $prestamo['libro_id']]
            );
            
            $this->db->getConnection()->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }
    
    public function listarPrestamosActivos($pagina = 1, $porPagina = 10) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            $query = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo,
                            DATEDIFF(CURRENT_DATE, p.fecha_prestamo) as dias_prestado
                     FROM {$this->table} p
                     JOIN usuarios u ON p.usuario_id = u.id
                     JOIN libros l ON p.libro_id = l.id
                     WHERE p.estado = 'activo'
                     ORDER BY p.fecha_prestamo DESC
                     LIMIT :limit OFFSET :offset";
            
            return $this->db->fetchAll($query, [
                ':limit' => $porPagina,
                ':offset' => $offset
            ]);
            
        } catch (Exception $e) {
            throw new Exception("Error al listar préstamos: " . $e->getMessage());
        }
    }
    
    public function obtenerHistorialUsuario($usuario_id) {
        try {
            return $this->db->fetchAll(
                "SELECT p.*, l.titulo as libro_titulo,
                        CASE 
                            WHEN p.estado = 'activo' THEN DATEDIFF(CURRENT_DATE, p.fecha_prestamo)
                            ELSE DATEDIFF(p.fecha_devolucion, p.fecha_prestamo)
                        END as dias_prestado
                 FROM {$this->table} p
                 JOIN libros l ON p.libro_id = l.id
                 WHERE p.usuario_id = :usuario_id
                 ORDER BY p.fecha_prestamo DESC",
                [':usuario_id' => $usuario_id]
            );
        } catch (Exception $e) {
            throw new Exception("Error al obtener historial: " . $e->getMessage());
        }
    }
    
    public function verificarPrestamosVencidos() {
        try {
            // Marcar préstamos vencidos (más de 14 días)
            return $this->db->executeQuery(
                "UPDATE {$this->table} 
                 SET estado = 'vencido'
                 WHERE estado = 'activo'
                 AND DATEDIFF(CURRENT_DATE, fecha_prestamo) > 14"
            )->rowCount();
        } catch (Exception $e) {
            throw new Exception("Error al verificar préstamos vencidos: " . $e->getMessage());
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            return $this->db->fetchOne(
                "SELECT 
                    COUNT(*) as total_prestamos,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as prestamos_activos,
                    SUM(CASE WHEN estado = 'vencido' THEN 1 ELSE 0 END) as prestamos_vencidos,
                    AVG(CASE 
                        WHEN estado = 'devuelto' 
                        THEN DATEDIFF(fecha_devolucion, fecha_prestamo)
                    END) as promedio_dias_prestamo
                 FROM {$this->table}"
            );
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
}