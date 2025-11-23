<?php
class Decision {
    public $id;
    public $name;
    public $description;
    public $createdAt;
    public $updatedAt;

    // Constructor para crear un objeto Decision a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'] ?? null;
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con una decisión individual
}
