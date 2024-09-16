<?php
include_once '../config/database.php';
include_once '../models/Activities.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ActivitiesControler {
    private $db;
    private $activities;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->activities = new Activities($this->db);
    }

    public function readActivities(): void {
        $stmt = $this->activities->readActivities();
        $activities = $stmt;
        echo json_encode($activities);
    }

    public function createActivity(): void {
        // Imprimir los datos recibidos para depuración
        echo "<pre>";
        print_r($_POST); // Para datos no binarios
        echo "</pre>";
        echo "<pre>";
        print_r($_FILES); // Para archivos subidos
        echo "</pre>";
    
        // Obtener datos del POST
        $description = isset($_POST['Description']) ? $_POST['Description'] : null;
        $activityName = isset($_POST['ActivityName']) ? $_POST['ActivityName'] : null;
        $activityDate = isset($_POST['ActivityDate']) ? $_POST['ActivityDate'] : null;

        // Verificar si se ha subido un archivo (si aplica)
        if (isset($_FILES['activityFile']) && $_FILES['activityFile']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['activityFile']['name']; // Nombre del archivo subido
            // Mover el archivo a una carpeta específica si es necesario
            move_uploaded_file($_FILES['activityFile']['tmp_name'], 'uploads/' . $_FILES['activityFile']['name']);
        } else {
            $file = null; // Valor predeterminado si no se subió un archivo
        }
    
        // Establecer valores en el modelo
        $this->activities->description = $description;
        $this->activities->activityName = $activityName;
        $this->activities->activityDate = $activityDate;

        // Crear la actividad
        if ($this->activities->createActivity()) {
            echo json_encode(["message" => "Actividad creada correctamente."]);
        } else {
            echo json_encode(["message" => "Error al crear la actividad."]);
        }
    }

    public function updateActivity(): void {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Imprimir los datos recibidos para depuración
        error_log(print_r($data, true));

        $this->activities->idActivities = $data['IdActivities'] ?? null;
        $this->activities->description = $data['Description'] ?? null;
        $this->activities->activityName = $data['ActivityName'] ?? null;
        $this->activities->activityDate = $data['ActivityDate'] ?? null;

        if ($this->activities->updateActivity()) {
            echo json_encode(["message" => "Actividad actualizada correctamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar la actividad."]);
        }
    }

    public function deleteActivity($id): void {
        $this->activities->idActivities = $id;

        if ($this->activities->deleteActivity()) {
            echo json_encode(["message" => "Actividad eliminada correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar la actividad."]);
        }
    }
}
?>
