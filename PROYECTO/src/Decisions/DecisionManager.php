<?php
class DecisionManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas las decisiones
    public function getAllDecisions() {
        $stmt = $this->db->query("SELECT * FROM decisions ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear una nueva decisión
    public function createDecision($decision) {
        $stmt = $this->db->prepare("INSERT INTO decisions (name, description) VALUES (?, ?)");
        return $stmt->execute([$decision->name, $decision->description]);
    }

    // Método para obtener una decisión por ID
    public function getDecisionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM decisions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar una decisión
    public function updateDecision($id, $name, $description) {
        $stmt = $this->db->prepare("UPDATE decisions SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // Método para eliminar una decisión
    public function deleteDecision($id) {
        $stmt = $this->db->prepare("DELETE FROM decisions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
