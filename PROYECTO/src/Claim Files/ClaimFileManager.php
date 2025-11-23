<?php
class ClaimFileManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todos los archivos de un reclamo
    public function getFilesByClaim($claimId) {
        $stmt = $this->db->prepare("SELECT * FROM claim_files WHERE claim_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$claimId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los archivos
    public function getAllClaimFiles() {
        $stmt = $this->db->query("SELECT * FROM claim_files ORDER BY uploaded_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para crear un nuevo archivo de reclamo
    public function createClaimFile($claimFile) {
        $stmt = $this->db->prepare("INSERT INTO claim_files (claim_id, filename, path, uploaded_by) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $claimFile->claimId, 
            $claimFile->filename, 
            $claimFile->path, 
            $claimFile->uploadedBy
        ]);
    }

    // Método para obtener un archivo por ID
    public function getClaimFileById($id) {
        $stmt = $this->db->prepare("SELECT * FROM claim_files WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para eliminar un archivo de reclamo
    public function deleteClaimFile($id) {
        $stmt = $this->db->prepare("DELETE FROM claim_files WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
