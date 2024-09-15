<?php
class User {
    private $conn;
    public $idUser;
    public $user;
    public $password;
    public $email;
    public $picture;
    public $idRol;
    

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
        $sql = "INSERT INTO user (User, Password, Email, Picture, IdRol) 
                VALUES (:user, :password, :description, :picture, :idRol)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':idRol', $this->idRol);
        
        return $stmt->execute();
    }

    public function updateUser(): mixed {
        $sql = "UPDATE user SET User = :user, Password = :password, Email = :email, 
                Picture = :picture, IdRol = :idRol WHERE IdUser = :idUser";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':idRol', $this->idRol);
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
