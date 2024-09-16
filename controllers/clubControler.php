<?php
include_once '../config/database.php';
include_once '../models/Club.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ClubControler {
    private $db;
    private $club;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->club = new Club($this->db);
    }

    public function readClubs(): void {
        $stmt = $this->club->readClubs();
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clubs);
    }

    public function createClub($data): void {
        // Obtener datos del POST
        $ClubName = isset($_POST['ClubName']) ? $_POST['ClubName'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $coach = isset($_POST['coach']) ? $_POST['coach'] : null;
        $idAnnouncement = isset($_POST['idAnnouncement']) ? $_POST['idAnnouncement'] : null;
        $idActivities = isset($_POST['idActivities']) ? $_POST['idActivities'] : null;

        // Establecer valores en el modelo
        $this->club->clubName = $ClubName;
        $this->club->description = $description;
        $this->club->coach = $coach;
        $this->club->idAnnouncement = $idAnnouncement;
        $this->club->idActivities = $idActivities;

        // Crear el club
        if ($this->club->createClub()) {
            echo json_encode(["message" => "Club creado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al crear club."]);
        }
    }

    public function updateClub($data): void {
        $data = json_decode(file_get_contents("php://input"), true);

        // Imprimir los datos recibidos para depuración
        error_log(print_r($data, true));

        $this->club->idClub = $data['IdClub'] ?? null;
        $this->club->ClubName = $data['ClubName'] ?? null;
        $this->club->description = $data['Description'] ?? null;
        $this->club->coach = $data['Coach'] ?? null;
        $this->club->idAnnouncement = $data['IdAnnouncement'] ?? null;
        $this->club->idActivities = $data['IdActivities'] ?? null;

        if ($this->club->updateClub()) {
            echo json_encode(["message" => "Club actualizado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar club."]);
        }
    }

    public function deleteClub($id): void {
        $this->club->idClub = $id;

        if ($this->club->deleteClub()) {
            echo json_encode(["message" => "Club eliminado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar club."]);
        }
    }
}
