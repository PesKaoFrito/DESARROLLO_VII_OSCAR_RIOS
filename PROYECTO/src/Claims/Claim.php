<?php
class Claim {
    public $id;
    public $claimNumber;
    public $insuredName;
    public $category;
    public $amount;
    public $status;

    public $analystId;
    public $supervisorId;
    public $createdAt;
    public $updatedAt;

    // Constructor para crear un objeto Claim a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->claimNumber = $data['claim_number'];
        $this->insuredName = $data['insured_name'];
        $this->category = $data['category'];
        $this->amount = $data['amount'];
        $this->status = $data['status'];
        $this->analystId = $data['analyst_id'];
        $this->supervisorId = $data['supervisor_id'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con un reclamo individual
}
