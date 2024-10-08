<?php
include_once '../config/database.php';
include_once '../models/Club.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

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

        // Modificar las rutas de las imágenes
        foreach ($clubs as &$club) {
            $club['picture'] = 'uploads/' . $club['Picture']; // Asumiendo que la columna en la base de datos se llama 'picture'
            $club['banner'] = 'uploads/' . $club['Banner'];   // Asumiendo que la columna en la base de datos se llama 'banner'
        }

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

    public function addUserToClub($idUser, $idClub)
    {
        $stmt = $this->db->prepare("INSERT INTO user_club (IdUser, IdClub) VALUES (:idUser, :idClub)");
        $stmt->bindParam(':idUser', $idUser);
        $stmt->bindParam(':idClub', $idClub);
        return $stmt->execute();
    }

    public function createClub(): void
    {
        // Obtener datos del POST
        $clubName = isset($_POST['ClubName']) ? $_POST['ClubName'] : null;
        $description = isset($_POST['Description']) ? $_POST['Description'] : null;
        $coach = isset($_POST['Coach']) ? $_POST['Coach'] : null;
        $idAnnouncement = isset($_POST['idAnnouncement']) ? $_POST['idAnnouncement'] : null;
        $idActivities = isset($_POST['idActivities']) ? $_POST['idActivities'] : null;
        // Inicializar variables para las rutas de las imágenes
        $picturePath = null;
        $bannerPath = null;
        // Manejar la subida de imágenes
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $pictureName = basename($_FILES['picture']['name']);
            $targetPicturePath = 'uploads/' . $pictureName;

            // Mover el archivo a la carpeta /uploads
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPicturePath)) {
                $picturePath = $pictureName;
            } else {
                echo json_encode(["message" => "Error al subir la imagen del club."]);
                return;
            }
        }
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $bannerName = basename($_FILES['banner']['name']);
            $targetBannerPath = 'uploads/' . $bannerName;

            // Mover el archivo a la carpeta /uploads
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $targetBannerPath)) {
                $bannerPath = $bannerName;
            } else {
                echo json_encode(["message" => "Error al subir el banner del club."]);
                return;
            }
        }
        // Establecer valores en el modelo
        $this->club->clubName = $clubName;
        $this->club->description = $description;
        $this->club->coach = $coach;
        $this->club->idAnnouncement = $idAnnouncement;
        $this->club->idActivities = $idActivities;
        $this->club->picture = $picturePath;
        $this->club->banner = $bannerPath;
        // Crear el club
        if ($this->club->createClub()) {
            // Obtener el IdUser de la sesión
            if (!isset($_SESSION['IdUser'])) {
                echo json_encode(["message" => "Usuario no autenticado."]);
                return;
            }

            $idUser = $_SESSION['IdUser']; // Ahora puedes obtener el IdUser


            // Obtener el IdClub del último club creado
            $idClub = $this->db->lastInsertId(); // Asegúrate de tener este método en tu modelo

            // Insertar en la tabla pivote user_club
            if ($this->addUserToClub($idUser, $idClub)) {
                echo json_encode(["message" => "Club creado correctamente y asignado al usuario."]);
            } else {
                echo json_encode(["message" => "Club creado, pero no se pudo asignar al usuario."]);
            }
        } else {
            echo json_encode(["message" => "Error al crear club."]);
        }

        error_log(print_r($_POST, true));
        error_log(print_r($_FILES, true));
    }

    public function updateClub(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        header('Content-Type: application/json');
        // Leer datos desde POST
        $idClub = $_POST['idClub'] ?? null;
        $clubName = isset($_POST['clubName']) ? $_POST['clubName']: null;
        $description =  isset($_POST['description']) ?$_POST['description'] : null;
        $coach = isset($_POST['coach']) ? $_POST['coach'] : null;
        $picture = isset($_FILES['picture']) ? $_FILES['picture']:null;
        $banner =isset($_FILES['banner']) ? $_FILES['banner']:null;
        $announcement = null; 
        $idActivities = null;
        $picturePath = null;
        $bannerPath = null;
        // Manejar la subida de imágenes
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $pictureName = basename($_FILES['picture']['name']);
            $targetPicturePath = 'uploads/' . $pictureName;

            // Mover el archivo a la carpeta /uploads
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPicturePath)) {
                $picturePath = $pictureName;
            } else {
                echo json_encode(["message" => "Error al subir la imagen del club."]);
                return;
            }
        }
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $bannerName = basename($_FILES['banner']['name']);
            $targetBannerPath = 'uploads/' . $bannerName;

            // Mover el archivo a la carpeta /uploads
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $targetBannerPath)) {
                $bannerPath = $bannerName;
            } else {
                echo json_encode(["message" => "Error al subir el banner del club."]);
                return;
            }
        }  

        $this->club->idClub = $idClub;
        $this->club->clubName = $clubName;
        $this->club->description = $description;
        $this->club->coach = $coach;
        $this->club->idAnnouncement = $announcement;
        $this->club->idActivities = $idActivities;
        $this->club->picture = $picturePath;
        $this->club->banner = $bannerPath;

       
        // Actualizar el club
        if ($this->club->updateClub()){
            echo json_encode(['succes' => "club creado exitosamente"
        ]);
        
        }else {
            echo json_encode(["error" => "Error al actualizar club."]);

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
