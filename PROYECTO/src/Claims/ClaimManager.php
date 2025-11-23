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

    // Método para buscar reclamos
    public function searchClaims($searchTerm) {
        $search = "%$searchTerm%";
        $stmt = $this->db->prepare("SELECT * FROM claims WHERE claim_number LIKE ? OR insured_name LIKE ? OR category LIKE ? ORDER BY created_at DESC");
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un reclamo por ID
    public function getClaimById($id) {
        $stmt = $this->db->prepare("SELECT * FROM claims WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar el estado de un reclamo
    public function updateClaimStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE claims SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Método para actualizar un reclamo completo
    public function updateClaim($id, $data) {
        $stmt = $this->db->prepare("UPDATE claims SET insured_name = ?, category = ?, amount = ?, status = ?, supervisor_id = ? WHERE id = ?");
        return $stmt->execute([
            $data['insured_name'],
            $data['category'],
            $data['amount'],
            $data['status'],
            $data['supervisor_id'],
            $id
        ]);
    }
}