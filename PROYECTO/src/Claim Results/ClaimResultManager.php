<?php
class ClaimResultManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener el resultado de un reclamo
    public function getResultByClaim($claimId) {
        $stmt = $this->db->prepare("SELECT * FROM claims_results WHERE claim_id = ?");
        $stmt->execute([$claimId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los resultados
    public function getAllClaimResults() {
        $stmt = $this->db->query("SELECT * FROM claims_results ORDER BY resolution_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo resultado de reclamo
    public function createClaimResult($claimResult) {
        $stmt = $this->db->prepare("INSERT INTO claims_results (claim_id, decision, comments, resolved_by) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([
            $claimResult->claimId, 
            $claimResult->decision, 
            $claimResult->comments, 
            $claimResult->resolvedBy
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Método para obtener un resultado por ID
    public function getClaimResultById($id) {
        $stmt = $this->db->prepare("SELECT * FROM claims_results WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un resultado de reclamo
    public function updateClaimResult($id, $decision, $comments, $resolvedBy) {
        $stmt = $this->db->prepare("UPDATE claims_results SET decision = ?, comments = ?, resolved_by = ?, resolution_date = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$decision, $comments, $resolvedBy, $id]);
    }

    // Método para eliminar un resultado de reclamo
    public function deleteClaimResult($id) {
        $stmt = $this->db->prepare("DELETE FROM claims_results WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
