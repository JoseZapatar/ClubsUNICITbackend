<?php
include_once '../config/database.php';
include_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User(conn: $this->db);
    }

    public function readUsers(): void {
        $stmt = $this->user->readUsers();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $users);
    }

    public function createUser($data): void {
        $this->user->user = $data['User'];  
        $this->user->password = $data['Password'];
        $this->user->description = $data['Description'];
        $this->user->picture = $data['Picture'];
        $this->user->idRol = $data['IdRol'];
        $this->user->name = $data['Name'];

        if($this->user->createUser()) {
            echo json_encode(value: ["message" => "Usuario creado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear usuario."]);
        }
    }

    public function updateUser($data): void {
        $this->user->idUser = $data['IdUser'];  
        $this->user->user = $data['User'];  
        $this->user->password = $data['Password'];
        $this->user->description = $data['Description'];
        $this->user->picture = $data['Picture'];
        $this->user->idRol = $data['IdRol'];
        $this->user->name = $data['Name'];

        if($this->user->updateUser()) {
            echo json_encode(value: ["message" => "Usuario actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar usuario."]);
        }
    }

    public function deleteUser($id): void {
        $this->user->idUser = $id;

        if($this->user->deleteUser()) {
            echo json_encode(value: ["message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar usuario."]);
        }
    }
}
