<?php
include_once '../config/database.php';
include_once '../models/Rol.php';

class RolControler {
    private $db;
    private $rol;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->rol = new Rol(conn: $this->db);
    }

    public function readRoles(): void {
        $stmt = $this->rol->readRoles();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $roles);
    }

    public function createRole($data): void {
        $this->rol->name = $data['Name'];
        $this->rol->permissions = $data['Permissions'];

        if($this->rol->createRole()) {
            echo json_encode(value: ["message" => "Rol creado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear rol."]);
        }
    }

    public function updateRole($data): void {
        $this->rol->idRol = $data['IdRol'];
        $this->rol->name = $data['Name'];
        $this->rol->permissions = $data['Permissions'];

        if($this->rol->updateRole()) {
            echo json_encode(value: ["message" => "Rol actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar rol."]);
        }
    }

    public function deleteRole($id): void {
        $this->rol->idRol = $id;

        if($this->rol->deleteRole()) {
            echo json_encode(value: ["message" => "Rol eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar rol."]);
        }
    }
}

