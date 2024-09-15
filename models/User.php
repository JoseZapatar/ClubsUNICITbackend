<?php
class User {
    private $conn;
    public $idUser;
    public $user;
    public $password;
    public $description;
    public $picture;
    public $idRol;
    public $name;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readUsers(): mixed {
        $sql = "SELECT * FROM user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser(): mixed {
        $sql = "INSERT INTO user (User, Password, Description, Picture, IdRol, Name) 
                VALUES (:user, :password, :description, :picture, :idRol, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':idRol', $this->idRol);
        $stmt->bindParam(':name', $this->name);
        return $stmt->execute();
    }

    public function updateUser(): mixed {
        $sql = "UPDATE user SET User = :user, Password = :password, Description = :description, 
                Picture = :picture, IdRol = :idRol, Name = :name WHERE IdUser = :idUser";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':idRol', $this->idRol);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':idUser', $this->idUser, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser(): mixed {
        $sql = "DELETE FROM user WHERE IdUser = :idUser";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idUser', $this->idUser, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
