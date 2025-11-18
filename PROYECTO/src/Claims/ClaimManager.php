<?php
class ClaimManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas los reclamos
    public function getAllClaims() {
        $stmt = $this->db->query("SELECT * FROM claims ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo reclamo
    public function createClaim($claim) {
        $stmt = $this->db->prepare("INSERT INTO claims (id, claim_number, insured_name, category, amount, status, analyst_id, supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $payload = [
            'id' => $claim->id,
            'claim_number' => $claim->claimNumber,
            'insured_name' => $claim->insuredName,
            'category' => $claim->category,
            'amount' => $claim->amount,
            'status' => $claim->status,
            'analyst_id' => $claim->analystId,
            'supervisor_id' => $claim->supervisorId
        ];
        return $stmt->execute(array_values($payload));
    }

    // Método para cambiar el estado de un reclamo (activo/inactivo)
    public function toggleClaim($id) {
        $stmt = $this->db->prepare("UPDATE claims SET status = NOT status WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Método para eliminar un reclamo
    public function deleteClaim($id) {
        $stmt = $this->db->prepare("DELETE FROM claims WHERE id = ?");
        return $stmt->execute([$id]);
    }
}