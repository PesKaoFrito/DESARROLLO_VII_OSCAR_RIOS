<?php
class RoleManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todos los roles
    public function getAllRoles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo rol
    public function createRole($role) {
        $stmt = $this->db->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
        return $stmt->execute([$role->name, $role->description]);
    }

    // Método para obtener un rol por ID
    public function getRoleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un rol
    public function updateRole($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE roles SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // Método para eliminar un rol
    public function deleteRole($id) {
        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
