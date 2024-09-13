<?php
class Registration {
    private $conn;
    public $idMatricula;
    public $idClub;
    public $idUser;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readRegistrations(): mixed {
        $sql = "SELECT * FROM registration";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createRegistration(): mixed {
        $sql = "INSERT INTO registration (IdClub, IdUser)  
                VALUES (:idClub, :idUser)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idClub', $this->idClub, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $this->idUser, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateRegistration(): mixed {
        $sql = "UPDATE registration SET IdClub = :idClub, IdUser = :idUser WHERE IdMatricula = :idMatricula";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idClub', $this->idClub, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $this->idUser, PDO::PARAM_INT);
        $stmt->bindParam(':idMatricula', $this->idMatricula, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteRegistration(): mixed {
        $sql = "DELETE FROM registration WHERE IdMatricula = :idMatricula";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idMatricula', $this->idMatricula, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
