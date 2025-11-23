<?php
class Policy {
    public $id;
    public $policyNumber;
    public $insuredName;
    public $insuredEmail;
    public $insuredPhone;
    public $insuredAddress;
    public $policyType;
    public $coverageAmount;
    public $premiumAmount;
    public $startDate;
    public $endDate;
    public $status;
    public $createdAt;
    public $updatedAt;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->policyNumber = $data['policy_number'] ?? null;
        $this->insuredName = $data['insured_name'];
        $this->insuredEmail = $data['insured_email'] ?? null;
        $this->insuredPhone = $data['insured_phone'] ?? null;
        $this->insuredAddress = $data['insured_address'] ?? null;
        $this->policyType = $data['policy_type'];
        $this->coverageAmount = $data['coverage_amount'];
        $this->premiumAmount = $data['premium_amount'];
        $this->startDate = $data['start_date'];
        $this->endDate = $data['end_date'];
        $this->status = $data['status'] ?? 'active';
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
    }

    public function isActive() {
        return $this->status === 'active' && strtotime($this->endDate) >= time();
    }
}
