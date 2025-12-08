<?php

class ReportManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener reporte de reclamos por período
     */
    public function getClaimsReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(c.created_at) as date,
                COUNT(*) as total_claims,
                SUM(c.amount) as total_amount,
                AVG(c.amount) as avg_amount
            FROM claims c
            WHERE c.created_at BETWEEN ? AND ?
            GROUP BY DATE(c.created_at)
            ORDER BY date DESC
        ");
        
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener reporte por categoría
     */
    public function getClaimsByCategory() {
        $stmt = $this->db->prepare("
            SELECT 
                cat.name as category,
                COUNT(c.id) as total_claims,
                SUM(c.amount) as total_amount
            FROM claims c
            JOIN categories cat ON c.category_id = cat.id
            GROUP BY cat.id, cat.name
            ORDER BY total_claims DESC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener reporte por estado
     */
    public function getClaimsByStatus() {
        $stmt = $this->db->prepare("
            SELECT 
                s.name as status,
                COUNT(c.id) as total_claims,
                SUM(c.amount) as total_amount
            FROM claims c
            JOIN statuses s ON c.status_id = s.id
            GROUP BY s.id, s.name
            ORDER BY total_claims DESC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estadísticas de pólizas
     */
    public function getPoliciesStats() {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(coverage_amount) as total_coverage,
                SUM(premium_amount) as total_premium
            FROM policies
        ");
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener rendimiento de analistas
     */
    public function getAnalystPerformance() {
        $stmt = $this->db->prepare("
            SELECT 
                u.name as analyst,
                COUNT(c.id) as total_claims,
                SUM(CASE WHEN s.name = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN s.name = 'rejected' THEN 1 ELSE 0 END) as rejected,
                AVG(c.amount) as avg_amount
            FROM claims c
            JOIN users u ON c.analyst_id = u.id
            JOIN statuses s ON c.status_id = s.id
            GROUP BY u.id, u.name
            ORDER BY total_claims DESC
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
