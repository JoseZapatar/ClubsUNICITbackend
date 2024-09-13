<?php
class Rol {
    private $conn;
    public $idRol;
    public $name;
    public $permissions;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readRoles(): mixed {
        $sql = "SELECT * FROM rol";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createRole(): mixed {
        $sql = "INSERT INTO rol (Name, Permissions)  
                VALUES (:name, :permissions)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':permissions', $this->permissions);
        return $stmt->execute();
    }

    public function updateRole(): mixed {
        $sql = "UPDATE rol SET Name = :name, Permissions = :permissions 
                WHERE IdRol = :idRol";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':permissions', $this->permissions);
        $stmt->bindParam(':idRol', $this->idRol, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteRole(): mixed {
        $sql = "DELETE FROM rol WHERE IdRol = :idRol";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idRol', $this->idRol, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
