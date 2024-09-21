<?php
include_once '../config/database.php';
include_once '../models/Activities.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ActivitiesControler
{
    private $db;
    private $activities;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->activities = new Activities($this->db);
    }

    public function readActivities(): void
    {
        $stmt = $this->activities->readActivities();
        $activities = $stmt;
        echo json_encode($activities);
    }
    public function getUserActivities()
    {
        if (isset($_SESSION['IdUser'])) {
            $userId = $_SESSION['IdUser'];

            try {
                // Obtener los clubes del usuario
                $query = "SELECT DISTINCT club.IdClub FROM club
                          INNER JOIN user_club ON club.IdClub = user_club.IdClub
                          WHERE user_club.IdUser = :userId";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':userId', $userId);
                $stmt->execute();

                $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $clubIds = array_column($clubs, 'IdClub');

                if (!empty($clubIds)) {
                    $clubIdsString = implode(",", $clubIds);

                    // Consulta modificada para usar la tabla pivot club_activity
                    $activitiesQuery = "SELECT DISTINCT activities.IdActivities, activities.ActivityDate, activities.Description, activities.ActivityName 
                                        FROM activities
                                        INNER JOIN club_activity ON activities.IdActivities = club_activity.IdActivities
                                        WHERE club_activity.IdClub IN ($clubIdsString)";
                    $activitiesStmt = $this->db->prepare($activitiesQuery);
                    $activitiesStmt->execute();
                    $activities = $activitiesStmt->fetchAll(PDO::FETCH_ASSOC);

                    // Responder con las actividades
                    header("Content-Type: application/json; charset=UTF-8");
                    echo json_encode([
                        "success" => true,
                        "activities" => $activities
                    ]);
                } else {
                    echo json_encode([
                        "success" => true,
                        "activities" => []
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Usuario no autenticado"
            ]);
        }
    }












    public function createActivity(): void
    {
        // Obtener datos del POST
        $description = $_POST['description'] ?? null;
        $activityName = $_POST['tituloEvento'] ?? null;
        $activityDate = $_POST['eventDate'] ?? null;
        $clubId = $_POST['clubId'] ?? null;  // Cambio aquí

        if ($description && $activityName && $activityDate && $clubId) {
            // Establecer los valores para el modelo Activities
            $this->activities->description = $description;
            $this->activities->activityName = $activityName;
            $this->activities->activityDate = $activityDate;

            // Crear la actividad
            if ($this->activities->createActivity()) {
                $lastActivityId = $this->db->lastInsertId(); // Obtener el ID de la actividad creada

                // Insertar en la tabla pivot club_activity
                try {
                    $pivotQuery = "INSERT INTO club_activity (IdClub, IdActivities) VALUES (:IdClub, :IdActivities)";
                    $stmt = $this->db->prepare($pivotQuery);
                    $stmt->bindParam(':IdClub', $clubId);
                    $stmt->bindParam(':IdActivities', $lastActivityId);

                    if ($stmt->execute()) {
                        http_response_code(201);
                        echo json_encode(["success" => true, "message" => "Actividad creada correctamente y vinculada al club."]);
                    } else {
                        echo json_encode(["success" => false, "message" => "Error al vincular la actividad con el club."]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al insertar en club_activity: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al vincular la actividad con el club."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Error al crear la actividad."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos para crear la actividad."]);
        }
    }



    public function updateActivity(): void
    {
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

    public function deleteActivity($id): void
    {
        $this->activities->idActivities = $id;

        if ($this->activities->deleteActivity()) {
            echo json_encode(["message" => "Actividad eliminada correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar la actividad."]);
        }
    }
}
?>