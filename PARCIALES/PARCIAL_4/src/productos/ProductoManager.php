<?php
class ProductoManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas los productos
    public function getAllProductos() {
        $stmt = $this->db->query("SELECT * FROM productos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo reclamo
    public function createProducto($producto) {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, categoria, precio, cantidad, fecha_registro) VALUES (?, ?, ?, ?, ?)");
        $payload = [
            'nombre' => $producto->nombre,
            'categoria' => $producto->categoria,
            'precio' => $producto->precio,
            'cantidad' => $producto->cantidad,
            'fecha_registro' => $producto->fecha_registro
        ];
        
        if ($stmt->execute(array_values($payload))) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Método para eliminar un reclamo
    public function deleteProducto($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Método para obtener un reclamo por ID
    public function getProductoById($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un reclamo completo
    public function updateClaim($id, $data) {
        $stmt = $this->db->prepare("UPDATE productos SET nombre = ?, categoria = ?, precio = ?, cantidad = ?, fecha_registro = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['categoria'],
            $data['precio'],
            $data['cantidad'],
            $data['monto'],
            $id
        ]);
    }
}