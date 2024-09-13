<?php
class Club {
    private $conn;
    public $idClub;
    public $picture;
    public $description;
    public $idAnnouncement;
    public $idActivities;
    public $banner;
    public $tutor;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function readClubs(): mixed {
        $sql = "SELECT * FROM club";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function createClub(): mixed {
        $sql = "INSERT INTO club (Picture, Description, IdAnnouncement, IdActivities, Banner, Tutor)  
                VALUES (:picture, :description, :idAnnouncement, :idActivities, :banner, :tutor)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
        $stmt->bindParam(':banner', $this->banner);
        $stmt->bindParam(':tutor', $this->tutor);
        return $stmt->execute();
    }

    public function updateClub(): mixed {
        $sql = "UPDATE club SET Picture = :picture, Description = :description, IdAnnouncement = :idAnnouncement,  // Cambiado a 'club'
                IdActivities = :idActivities, Banner = :banner, Tutor = :tutor WHERE IdClub = :idClub";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
        $stmt->bindParam(':banner', $this->banner);
        $stmt->bindParam(':tutor', $this->tutor);
        $stmt->bindParam(':idClub', $this->idClub, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteClub(): mixed {
        $sql = "DELETE FROM club WHERE IdClub = :idClub";  
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idClub', $this->idClub, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
