<?php
// Incluir la clase de conexión a la base de datos
include_once '../config/database.php';
include_once '../models/UserClub.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

class UserClubControler
{
    private $conn;

    // Constructor que establece la conexión con la base de datos
    public function __construct()
    {
        // Iniciar la sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Conectar a la base de datos
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para obtener los clubes de un usuario específico (usando la sesión)
    public function getUserClubs()
    {
        if (isset($_SESSION['IdUser'])) {
            $userId = $_SESSION['IdUser'];

            try {
                $query = "SELECT club.* FROM club
                          INNER JOIN user_club ON club.IdClub = user_club.IdClub
                          WHERE user_club.IdUser = :userId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':userId', $userId);
                $stmt->execute();

                $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode([
                    "success" => true,
                    "user" => [
                        "IdUser" => $userId,
                        "username" => $_SESSION['username'] ?? null,
                        "email" => $_SESSION['email'] ?? null,
                        "idRol" => $_SESSION['idRol'] ?? null,
                    ],
                    "clubs" => $clubs
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
            }
        } else {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode([
                "success" => false,
                "message" => "Usuario no autenticado"
            ]);
        }
    }

    public function getUserAnnouncements()
    {
        if (isset($_SESSION['IdUser'])) {
            $userId = $_SESSION['IdUser'];

            try {
                $query = "SELECT announcement.Name, announcement.Description FROM announcement
                          RIGHT JOIN club ON club.IdAnnouncement = announcement.IdAnnouncement
                          RIGHT JOIN user_club ON club.IdClub = user_club.IdClub
                          WHERE user_club.IdUser = :userId";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':userId', $userId);
                $this->conn->exec("SET NAMES 'utf8mb4'");
                $stmt->execute();

                $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);



                header("Content-Type: application/json; charset=UTF-8");
                $json = json_encode([
                    "success" => true,
                    "announcements" => $announcements
                ], JSON_UNESCAPED_UNICODE);

                if ($json === false) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Error al codificar JSON: " . json_last_error_msg()
                    ]);
                } else {
                    echo $json;
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
            }
        } else {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode([
                "success" => false,
                "message" => "Usuario no autenticado"
            ]);
        }
    }
    public function getAnnouncementsByClub()
{
    // Verificar que la solicitud sea POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar que el cuerpo contenga el clubId
        if (isset($data['IdClub'])) {
            $clubId = $data['IdClub'];

            try {
                // Consulta para obtener los anuncios de un club específico usando la tabla pivote club_announcement
                $query = "SELECT announcement.Name, announcement.Description 
                          FROM announcement
                          INNER JOIN club_announcement ON announcement.IdAnnouncement = club_announcement.IdAnnouncement
                          WHERE club_announcement.IdClub = :clubId";

                // Preparar la consulta SQL
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':clubId', $clubId);
                $this->conn->exec("SET NAMES 'utf8mb4'");
                $stmt->execute();

                // Obtener los resultados
                $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Enviar la respuesta en formato JSON
                header("Content-Type: application/json; charset=UTF-8");
                $json = json_encode([
                    "success" => true,
                    "announcements" => $announcements
                ], JSON_UNESCAPED_UNICODE);

                if ($json === false) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Error al codificar JSON: " . json_last_error_msg()
                    ]);
                } else {
                    echo $json;
                }

            } catch (PDOException $e) {
                // Manejar errores de la base de datos
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
            }
        } else {
            // Respuesta en caso de que no se haya enviado el IdClub
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Id del club no proporcionado"
            ]);
        }
    } else {
        // Respuesta si el método no es POST
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido"
        ]);
    }
}



    // Método para registrar al usuario en un club (usando la sesión)
    public function registerUserClub($data)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['IdUser']) && isset($data['IdClub'])) {
                $userId = $_SESSION['IdUser'];
                $clubId = $data['IdClub'];

                // Instanciar el modelo de UserClub
                $userClub = new UserClub();

                // Registrar al usuario en el club
                $response = $userClub->registerUserToClub($userId, $clubId);

                // Devolver la respuesta en formato JSON
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($response);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "IdUser e IdClub son obligatorios"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Método no permitido"
            ]);
        }
    }

}
