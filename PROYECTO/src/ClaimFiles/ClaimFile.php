<?php
class ClaimFile {
    public $id;
    public $claimId;
    public $filename;
    public $path;
    public $uploadedBy;
    public $uploadedAt;

    // Constructor para crear un objeto ClaimFile a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->claimId = $data['claim_id'];
        $this->filename = $data['filename'];
        $this->path = $data['path'];
        $this->uploadedBy = $data['uploaded_by'];
        $this->uploadedAt = $data['uploaded_at'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con un archivo de reclamo individual
}
