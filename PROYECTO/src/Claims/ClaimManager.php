<?php
class ClaimManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas los reclamos con información relacionada
    public function getAllClaims() {
        $stmt = $this->db->query("
            SELECT 
                c.*,
                p.policy_number,
                p.policy_type,
                cat.name as category_name,
                s.name as status_name,
                u.name as analyst_name
            FROM claims c
            LEFT JOIN policies p ON c.policy_id = p.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN statuses s ON c.status_id = s.id
            LEFT JOIN users u ON c.analyst_id = u.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo reclamo
<<<<<<< HEAD
    public function createClaim($data) {
        // Generar número de reclamo único
        $claimNumber = 'CLM-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        // Obtener el ID del estado "pending" por defecto
        $statusStmt = $this->db->prepare("SELECT id FROM statuses WHERE name = 'pending' LIMIT 1");
        $statusStmt->execute();
        $statusId = $statusStmt->fetchColumn() ?: 1;
        
        // Insertar el reclamo con todos los campos del formulario
        $stmt = $this->db->prepare("
            INSERT INTO claims (
                claim_number, 
                policy_id,
                category_id,
                status_id,
                insured_name, 
                insured_phone,
                insured_email,
                amount, 
                description,
                analyst_id, 
                supervisor_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)
        ");
        
        $result = $stmt->execute([
            $claimNumber,
            $data['policy_id'] ?? null,
            $data['category_id'],
            $statusId,
            $data['insured_name'],
            $data['insured_phone'] ?? null,
            $data['insured_email'] ?? null,
            $data['amount'],
            $data['description'] ?? null,
            $data['analyst_id'] ?? null
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
=======
    public function createClaim($claim) {
        $stmt = $this->db->prepare("INSERT INTO claims (claim_number, insured_name, category, amount, status, analyst_id, supervisor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $payload = [
            'claim_number' => $claim->claimNumber,
            'insured_name' => $claim->insuredName,
            'category' => $claim->category,
            'amount' => $claim->amount,
            'status' => $claim->status,
            'analyst_id' => $claim->analystId,
            'supervisor_id' => $claim->supervisorId
        ];
        
        if ($stmt->execute(array_values($payload))) {
            return $this->db->lastInsertId();
        }
        return false;
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
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
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                p.policy_number,
                p.policy_type,
                cat.name as category_name,
                s.name as status_name,
                u.name as analyst_name
            FROM claims c
            LEFT JOIN policies p ON c.policy_id = p.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN statuses s ON c.status_id = s.id
            LEFT JOIN users u ON c.analyst_id = u.id
            WHERE c.claim_number LIKE ? OR c.insured_name LIKE ? OR cat.name LIKE ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un reclamo por ID con información de la póliza
    public function getClaimById($id) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                p.policy_number,
                p.insured_name as policy_holder,
                p.policy_type,
                p.coverage_amount,
                cat.name as category_name,
                s.name as status_name,
                u.name as analyst_name
            FROM claims c
            LEFT JOIN policies p ON c.policy_id = p.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN statuses s ON c.status_id = s.id
            LEFT JOIN users u ON c.analyst_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar el estado de un reclamo
    public function updateClaimStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE claims SET status_id = ? WHERE id = ?");
        return $stmt->execute([$statusId, $id]);
    }

    // Método para actualizar un reclamo completo
    public function updateClaim($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE claims 
            SET insured_name = ?, 
                insured_phone = ?,
                insured_email = ?,
                category_id = ?, 
                amount = ?, 
                description = ?,
                status_id = ?, 
                supervisor_id = ? 
            WHERE id = ?");
        return $stmt->execute([
            $data['insured_name'],
            $data['insured_phone'] ?? null,
            $data['insured_email'] ?? null,
            $data['category_id'],
            $data['amount'],
            $data['description'] ?? null,
            $data['status_id'],
            $data['supervisor_id'] ?? null,
            $id
        ]);
    }

    // Método para obtener reclamos por analista
    public function getClaimsByAnalyst($analystId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                p.policy_number,
                p.policy_type,
                cat.name as category_name,
                s.name as status_name,
                u.name as analyst_name
            FROM claims c
            LEFT JOIN policies p ON c.policy_id = p.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN statuses s ON c.status_id = s.id
            LEFT JOIN users u ON c.analyst_id = u.id
            WHERE c.analyst_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$analystId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener reclamos supervisados por un supervisor
    public function getClaimsBySupervisor($supervisorId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                p.policy_number,
                p.policy_type,
                cat.name as category_name,
                s.name as status_name,
                u.name as analyst_name
            FROM claims c
            LEFT JOIN policies p ON c.policy_id = p.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN statuses s ON c.status_id = s.id
            LEFT JOIN users u ON c.analyst_id = u.id
            WHERE c.supervisor_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$supervisorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para verificar si un usuario puede editar un reclamo
    public function canUserEditClaim($claimId, $userId, $userRole) {
        if ($userRole === 'admin') {
            return true;
        }

        $claim = $this->getClaimById($claimId);
        if (!$claim) {
            return false;
        }

        if ($userRole === 'supervisor') {
            return $claim['supervisor_id'] == $userId;
        }

        if ($userRole === 'analyst') {
            return $claim['analyst_id'] == $userId;
        }

        return false;
    }
}