<?php
class StatusManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todos los estados
    public function getAllStatuses() {
        $stmt = $this->db->query("SELECT * FROM statuses ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo estado
    public function createStatus($status) {
        $stmt = $this->db->prepare("INSERT INTO statuses (name, description) VALUES (?, ?)");
        return $stmt->execute([$status->name, $status->description]);
    }

    // Método para obtener un estado por ID
    public function getStatusById($id) {
        $stmt = $this->db->prepare("SELECT * FROM statuses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un estado
    public function updateStatus($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE statuses SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // Método para eliminar un estado
    public function deleteStatus($id) {
        $stmt = $this->db->prepare("DELETE FROM statuses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
