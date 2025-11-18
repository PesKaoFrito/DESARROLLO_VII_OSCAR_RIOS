<?php
require_once 'config.php';

class Libros {
    private $db;
    private $table = 'libros';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function crear($datos) {
        try {
            return $this->db->insert($this->table, $datos);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                throw new Exception("El ISBN ya existe en la base de datos");
            }
            throw $e;
        }
    }
    
    public function listar($pagina = 1, $porPagina = 10, $filtros = []) {
        try {
            $offset = ($pagina - 1) * $porPagina;
            $where = [];
            $params = [];
            
            if (!empty($filtros['busqueda'])) {
                $where[] = "(titulo LIKE :busqueda OR autor LIKE :busqueda OR isbn LIKE :busqueda)";
                $params[':busqueda'] = "%{$filtros['busqueda']}%";
            }
            
            if (isset($filtros['disponibles']) && $filtros['disponibles']) {
                $where[] = "cantidad_disponible > 0";
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";
            
            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM {$this->table} $whereClause",
                $params
            )['total'];
            
            $query = "SELECT * FROM {$this->table} 
                     $whereClause 
                     ORDER BY created_at DESC 
                     LIMIT :limit OFFSET :offset";
                     
            $params[':limit'] = $porPagina;
            $params[':offset'] = $offset;
            
            $libros = $this->db->fetchAll($query, $params);
            
            return [
                'libros' => $libros,
                'total' => $total,
                'paginas' => ceil($total / $porPagina),
                'pagina_actual' => $pagina
            ];
            
        } catch (PDOException $e) {
            throw new Exception("Error al listar libros: " . $e->getMessage());
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            return $this->db->update(
                $this->table,
                $datos,
                'id = :id',
                [':id' => $id]
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("El ISBN ya existe en la base de datos");
            }
            throw $e;
        }
    }
    
    public function eliminar($id) {
        try {
            // Verificar si hay préstamos activos
            $prestamos = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM prestamos 
                 WHERE libro_id = :id AND estado = 'activo'",
                [':id' => $id]
            );
            
            if ($prestamos['total'] > 0) {
                throw new Exception("No se puede eliminar el libro porque tiene préstamos activos");
            }
            
            return $this->db->delete($this->table, 'id = :id', [':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar libro: " . $e->getMessage());
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            return $this->db->fetchAll("
                SELECT 
                    COUNT(*) as total_libros,
                    SUM(cantidad_disponible) as total_disponibles,
                    (SELECT COUNT(*) FROM prestamos WHERE estado = 'activo') as prestamos_activos
                FROM {$this->table}
            ");
        } catch (PDOException $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
    
    public function buscarDuplicados() {
        return $this->db->fetchAll("
            SELECT titulo, autor, COUNT(*) as total
            FROM {$this->table}
            GROUP BY titulo, autor
            HAVING total > 1
        ");
    }
}