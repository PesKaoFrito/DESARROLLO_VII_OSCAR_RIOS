<?php
class PolicyManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPolicies() {
        $stmt = $this->db->query("SELECT * FROM policies ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivePolicies() {
        $stmt = $this->db->query("SELECT * FROM policies WHERE status = 'active' AND end_date >= CURDATE() ORDER BY policy_number");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPolicyById($id) {
        $stmt = $this->db->prepare("SELECT * FROM policies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPolicyByNumber($policyNumber) {
        $stmt = $this->db->prepare("SELECT * FROM policies WHERE policy_number = ?");
        $stmt->execute([$policyNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchPolicies($search) {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("SELECT * FROM policies WHERE policy_number LIKE ? OR insured_name LIKE ? OR insured_email LIKE ? ORDER BY created_at DESC");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPolicy($data) {
        $stmt = $this->db->prepare("INSERT INTO policies (policy_number, insured_name, insured_email, insured_phone, insured_address, policy_type, coverage_amount, premium_amount, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $data['policy_number'],
            $data['insured_name'],
            $data['insured_email'],
            $data['insured_phone'],
            $data['insured_address'] ?? '',
            $data['policy_type'],
            $data['coverage_amount'],
            $data['premium_amount'],
            $data['start_date'],
            $data['end_date'],
            $data['status'] ?? 'active'
        ]);
        return $result ? $this->db->lastInsertId() : false;
    }

    public function updatePolicy($id, $data) {
        $stmt = $this->db->prepare("UPDATE policies SET policy_number = ?, insured_name = ?, insured_email = ?, insured_phone = ?, insured_address = ?, policy_type = ?, coverage_amount = ?, premium_amount = ?, start_date = ?, end_date = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['policy_number'],
            $data['insured_name'],
            $data['insured_email'],
            $data['insured_phone'],
            $data['insured_address'],
            $data['policy_type'],
            $data['coverage_amount'],
            $data['premium_amount'],
            $data['start_date'],
            $data['end_date'],
            $data['status'],
            $id
        ]);
    }

    public function deletePolicy($id) {
        $stmt = $this->db->prepare("DELETE FROM policies WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPolicyStats() {
        $stmt = $this->db->query("SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'active' THEN 1 END) as active,
            COUNT(CASE WHEN status = 'expired' THEN 1 END) as expired,
            SUM(coverage_amount) as total_coverage,
            SUM(premium_amount) as total_premiums
            FROM policies");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
