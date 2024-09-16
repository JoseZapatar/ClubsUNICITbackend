<?php
include_once '../config/database.php';
include_once '../models/Club.php';

class ClubControler {
    private $db;
    private $club;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->club = new Club(conn: $this->db);
    }

    public function readClubs(): void {
        $stmt = $this->club->readClubs();
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $clubs);
    }

    public function createClub($data): void {
        $this->club->picture = $data['Picture'];
        $this->club->description = $data['Description'];
        $this->club->banner = $data['Banner'];
        $this->club->ClubName = $data['ClubName'];
        $this->club->coach = $data['Coach'];
        $this->club->idAnnouncement = $data['IdAnnouncement'];
        $this->club->idActivities = $data['IdActivities'];
        
        

        if ($this->club->createClub()) {
            echo json_encode(value: ["message" => "Club creado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear club."]);
        }
    }

    public function updateClub($data): void {
        $this->club->idClub = $data['IdClub'];
        $this->club->picture = $data['Picture'];
        $this->club->description = $data['Description'];
        $this->club->banner = $data['Banner'];
        $this->club->ClubName = $data['ClubName'];
        $this->club->coach = $data['Coach'];
        $this->club->idAnnouncement = $data['IdAnnouncement'];
        $this->club->idActivities = $data['IdActivities'];

        if ($this->club->updateClub()) {
            echo json_encode(value: ["message" => "Club actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar club."]);
        }
    }

    public function deleteClub($id): void {
        $this->club->idClub = $id;

        if ($this->club->deleteClub()) {
            echo json_encode(value: ["message" => "Club eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar club."]);
        }
    }
}
