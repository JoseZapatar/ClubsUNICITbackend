<?php
class Calendary {
    private $conn;
    public $idCalendary;
    public $name;
    public $idActivities;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readCalendaries(): mixed {
        $sql = "SELECT * FROM Calendary";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createCalendary(): mixed {
        $sql = "INSERT INTO Calendary (Name, IdActivities)  
                VALUES (:name, :idActivities)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':idActivities', $this->idActivities);
        return $stmt->execute();
    }

    public function updateCalendary(): mixed {
        $sql = "UPDATE Calendary SET Name = :name, IdActivities = :idActivities 
                WHERE IdCalendary = :idCalendary";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':idActivities', $this->idActivities);
        $stmt->bindParam(':idCalendary', $this->idCalendary, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCalendary(): mixed {
        $sql = "DELETE FROM Calendary WHERE IdCalendary = :idCalendary";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idCalendary', $this->idCalendary, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
