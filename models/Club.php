<?php
class Club {
    private $conn;
    public $idClub;
    public $picture;
    public $description;
    public $banner;
    public $clubName;
    public $coach;
    public $idAnnouncement;
    public $idActivities;
    
    

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
        $sql = "INSERT INTO club (Picture, Description, Banner, ClubName, Coach, IdAnnouncement, IdActivities)  
                VALUES (:picture, :description, :banner, :clubName, :coach, :idAnnouncement, :idActivities)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':banner', $this->banner);
        $stmt->bindParam('clubName', $this->clubName);
        $stmt->bindParam(':coach', $this->coach);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateClub(): mixed {
        $sql = "UPDATE club SET Picture = :picture, Description = :description, Banner = :banner, ClubName = :clubName, Coach = :coach, IdAnnouncement = :idAnnouncement, 
        IdActivities = :idActivities WHERE IdClub = :idClub";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':picture', $this->picture);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':banner', $this->banner);
        $stmt->bindParam('clubName', $this->clubName);
        $stmt->bindParam(':coach', $this->coach);
        $stmt->bindParam(':idAnnouncement', $this->idAnnouncement, PDO::PARAM_INT);
        $stmt->bindParam(':idActivities', $this->idActivities, PDO::PARAM_INT);
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
