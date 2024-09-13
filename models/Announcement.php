<?php
class Announcement {
    private $conn;
    public $idAnnouncement;
    public $description;
    public $picture;
    public $name;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readAnnouncements(): mixed {
        $sql = "SELECT * FROM announcement";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createAnnouncement(): mixed {
        $sql = "INSERT INTO announcement (Description, Picture, Name)  
                VALUES (:description, :picture, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':name', $this->name);
        return $stmt->execute();
    }

    public function updateAnnouncement(): mixed {
        $sql = "UPDATE announcement SET Description = :description, Picture = :picture,  
                Name = :name WHERE IdAnnouncement = :idAnnouncement";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteAnnouncement(): mixed {
        $sql = "DELETE FROM announcement WHERE IdAnnouncement = :idAnnouncement";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
