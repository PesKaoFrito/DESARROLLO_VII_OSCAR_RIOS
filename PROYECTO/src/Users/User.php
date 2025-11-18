<?php
class User {
    public $id;
    public $name;
    public $email;
    public $passwordHash;
    public $role;
    public $createdAt;
    public $updatedAt;

    // Constructor para crear un objeto User a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->passwordHash = $data['password_hash'];
        $this->role = $data['role'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con un usuario individual
}