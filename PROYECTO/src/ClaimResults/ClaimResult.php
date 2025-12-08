<?php
class ClaimResult {
    public $id;
    public $claimId;
    public $decision;
    public $comments;
    public $resolutionDate;
    public $resolvedBy;

    // Constructor para crear un objeto ClaimResult a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->claimId = $data['claim_id'];
        $this->decision = $data['decision'];
        $this->comments = $data['comments'] ?? null;
        $this->resolutionDate = $data['resolution_date'];
        $this->resolvedBy = $data['resolved_by'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con un resultado de reclamo individual
}
