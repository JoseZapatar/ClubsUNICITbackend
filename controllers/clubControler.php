<?php
include_once '../config/database.php';
include_once '../models/Club.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ClubControler{
    private $db;
    private $club;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->club = new Club($this->db);
    }

    public function readClubs(): void
    {
        $stmt = $this->club->readClubs();
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clubs);
    }

    public function createClub($data): void
    {
        // Imprimir los datos recibidos para depuración
        echo "<pre>";
        print_r($_POST); // Para datos no binarios
        echo "</pre>";
        echo "<pre>";
        print_r($_FILES); // Para archivos subidos
        echo "</pre>";

        // Obtener datos del POST
        $ClubName = isset($_POST['ClubName']) ? $_POST['ClubName'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $coach = isset($_POST['coach']) ? $_POST['coach'] : null;
        $idAnnouncement = isset($_POST['idAnnouncement']) ? $_POST['idAnnouncement'] : null;
        $idActivities = isset($_POST['idActivities']) ? $_POST['idActivities'] : null;

        // Verificar si se ha subido un archivo de imagen para el club
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $picture = $_FILES['picture']['name'];
            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $_FILES['picture']['name']);
        } else {
            $picture = null;
        }

        // Verificar si se ha subido un banner para el club
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $banner = $_FILES['banner']['name'];
            move_uploaded_file($_FILES['banner']['tmp_name'], 'uploads/' . $_FILES['banner']['name']);
        } else {
            $banner = null;
        }

        // Establecer valores en el modelo
        $this->club->clubName = $ClubName;
        $this->club->description = $description;
        $this->club->coach = $coach;
        $this->club->idAnnouncement = $idAnnouncement;
        $this->club->idActivities = $idActivities;
        $this->club->picture = $picture;
        $this->club->banner = $banner;

        // Crear el club
        if ($this->club->createClub()) {
            echo json_encode(["message" => "Club creado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al crear club."]);
        }
    }

    public function updateClub($data): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Imprimir los datos recibidos para depuración
        error_log(print_r($data, true));

        $this->club->idClub = $data['IdClub'] ?? null;
        $this->club->ClubName = $data['ClubName'] ?? null;
        $this->club->description = $data['Description'] ?? null;
        $this->club->coach = $data['Coach'] ?? null;
        $this->club->idAnnouncement = $data['IdAnnouncement'] ?? null;
        $this->club->idActivities = $data['IdActivities'] ?? null;
        $this->club->picture = $data['Picture'] ?? null;
        $this->club->banner = $data['Banner'] ?? null;

        if ($this->club->updateClub()) {
            echo json_encode(["message" => "Club actualizado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar club."]);
        }
    }

    public function deleteClub($id): void
    {
        $this->club->idClub = $id;

        if ($this->club->deleteClub()) {
            echo json_encode(["message" => "Club eliminado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar club."]);
        }
    }
}
