<?php
include_once '../config/database.php';
include_once '../models/Club.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ClubControler
{
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

    public function searchClubs($searchTerm): void
    {
        // Verifica si el término de búsqueda está vacío
        if (empty($searchTerm)) {
            // Si está vacío, no aplicar el filtro y buscar todos los clubes
            $stmt = $this->club->searchClubs(''); // Pasar una cadena vacía o modificar el método en el modelo
        } else {
            // Filtrar el término de búsqueda
            $searchTerm = '%' . $searchTerm . '%'; // Para usar en LIKE
            $stmt = $this->club->searchClubs($searchTerm);
        }

        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clubs) {
            echo json_encode($clubs);
        } else {
            echo json_encode([]);
        }
    }

//Si
    public function createClub(): void
    {
        // Obtener datos del POST
        $clubName = isset($_POST['ClubName']) ? $_POST['ClubName'] : null;
        $description = isset($_POST['Description']) ? $_POST['Description'] : null;
        $coach = isset($_POST['Coach']) ? $_POST['Coach'] : null;
        $idAnnouncement = isset($_POST['idAnnouncement']) ? $_POST['idAnnouncement'] : null;
        $idActivities = isset($_POST['idActivities']) ? $_POST['idActivities'] : null;
        $picture = isset($_FILES['picture']) ? $_FILES['picture']['name'] : null;
        $banner = isset($_FILES['banner']) ? $_FILES['banner']['name'] : null;

        // Establecer valores en el modelo
        $this->club->clubName = $clubName;
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
        error_log(print_r($_POST, true));
        error_log(print_r($_FILES, true));

    }

    public function updateClub(): void
    {
        // Leer datos crudos del cuerpo de la solicitud
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Depurar los datos recibidos
        error_log("Datos recibidos para actualizar el club:");
        error_log(print_r($data, true));

        // Asignar valores desde el array $data
        $idClub = isset($data['idClub']) ? $data['idClub'] : null;
        $clubName = isset($data['clubName']) ? $data['clubName'] : null;
        $description = isset($data['description']) ? $data['description'] : null;
        $coach = isset($data['coach']) ? $data['coach'] : null;
        $idAnnouncement = isset($data['idAnnouncement']) ? $data['idAnnouncement'] : null;
        $idActivities = isset($data['idActivities']) ? $data['idActivities'] : null;
        $picture = isset($data['picture']) ? $data['picture'] : null;
        $banner = isset($data['banner']) ? $data['banner'] : null;

        // Depurar los valores individuales
        error_log("Valores después de la asignación:");
        error_log("idClub: " . $idClub);
        error_log("clubName: " . $clubName);
        error_log("description: " . $description);
        error_log("coach: " . $coach);
        error_log("idAnnouncement: " . $idAnnouncement);
        error_log("idActivities: " . $idActivities);
        error_log("picture: " . $picture);
        error_log("banner: " . $banner);

        // Establecer valores en el modelo
        $this->club->idClub = $idClub;
        $this->club->clubName = $clubName;
        $this->club->description = $description;
        $this->club->coach = $coach;
        $this->club->idAnnouncement = $idAnnouncement;
        $this->club->idActivities = $idActivities;
        $this->club->picture = $picture;
        $this->club->banner = $banner;

        // Actualizar el club
        if ($this->club->updateClub()) {
            echo json_encode(["message" => "Club actualizado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar club."]);
        }
    }


    public function deleteClub(): void
    {
        // Obtener el ID desde POST
        $idClub = isset($_POST['IdClub']) ? $_POST['IdClub'] : null;

        // Establecer el valor en el modelo
        $this->club->idClub = $idClub;

        // Eliminar el club
        if ($this->club->deleteClub()) {
            echo json_encode(["message" => "Club eliminado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar club."]);
        }

        // Log para depuración
        error_log(print_r($_POST, true));
    }

}
