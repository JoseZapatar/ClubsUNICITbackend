<?php
include_once '../config/database.php';
include_once '../models/Announcement.php';

class AnnouncementControler {
    private $db;
    private $announcement;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->announcement = new Announcement(conn: $this->db);
    }

    public function readAnnouncements(): void {
        $stmt = $this->announcement->readAnnouncements();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $announcements);
    }

    public function createAnnouncement(): void {
        $this->announcement->description = $_POST['description'] ?? '';
        $this->announcement->name = $_POST['tituloAnuncio'] ?? '';
    
        // Manejo del archivo
        if (isset($_FILES['picture'])) {
            $this->announcement->picture = file_get_contents($_FILES['picture']['tmp_name']);
        } else {
            $this->announcement->picture = null;
        }
    
        if ($this->announcement->createAnnouncement()) {
            echo json_encode(["message" => "Anuncio creado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al crear anuncio."]);
        }
    }

    public function updateAnnouncement($data): void {
        $this->announcement->idAnnouncement = $data['IdAnnouncement'];
        $this->announcement->description = $data['Description'];
        $this->announcement->picture = $data['Picture'];
        $this->announcement->name = $data['Name'];

        if ($this->announcement->updateAnnouncement()) {
            echo json_encode(value: ["message" => "Anuncio actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar anuncio."]);
        }
    }

    public function deleteAnnouncement($id): void {
        $this->announcement->idAnnouncement = $id;

        if ($this->announcement->deleteAnnouncement()) {
            echo json_encode(value: ["message" => "Anuncio eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar anuncio."]);
        }
    }
}
