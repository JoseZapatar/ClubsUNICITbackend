<?php
// Incluir la clase de conexión a la base de datos
include_once '../config/database.php';
include_once '../models/UserClub.php';

class UserClubControler
{
    private $conn;

    // Constructor que establece la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para obtener los clubes de un usuario específico
    public function getUserClubs()
    {
        // Verificar que el método sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener los datos enviados en la solicitud
            $data = json_decode(file_get_contents("php://input"), true);

            // Verificar que se haya proporcionado el IdUser
            if (isset($data['IdUser'])) {
                $userId = $data['IdUser'];

                try {
                    // Consulta SQL para obtener los clubes asociados al usuario
                    $query = "SELECT club.* FROM club
                              INNER JOIN user_club ON club.IdClub = user_club.IdClub
                              WHERE user_club.IdUser = :userId";

                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':userId', $userId);

                    // Ejecutar la consulta
                    $stmt->execute();

                    // Verificar si se encontraron resultados
                    if ($stmt->rowCount() > 0) {
                        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Retornar los clubes del usuario
                        echo json_encode([
                            "success" => true,
                            "clubs" => $clubs
                        ]);
                    } else {
                        // Si no se encontraron clubes
                        echo json_encode([
                            "success" => false,
                            "message" => "No se encontraron clubes para este usuario"
                        ]);
                    }
                } catch (PDOException $e) {
                    // Manejar errores de base de datos
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Error en la consulta: " . $e->getMessage()
                    ]);
                }
            } else {
                // Si no se proporciona el IdUser
                echo json_encode([
                    "success" => false,
                    "message" => "Por favor, proporcione el ID del usuario"
                ]);
            }
        } else {
            // Si el método no es GET
            http_response_code(405);
            echo json_encode([
                "success" => false,
                "message" => "Método no permitido, utilice GET"
            ]);
        }
    }
    public function registerUserClub($data)
    {
        // Verificar que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar que los campos IdUser y IdClub estén presentes
            if (isset($data['IdUser']) && isset($data['IdClub'])) {
                $userId = $data['IdUser'];
                $clubId = $data['IdClub'];

                // Instanciar el modelo de UserClub
                $userClub = new UserClub();

                // Registrar al usuario en el club
                $response = $userClub->registerUserToClub($userId, $clubId);

                // Devolver la respuesta en formato JSON
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($response);
            } else {
                // Si faltan campos
                echo json_encode([
                    "success" => false,
                    "message" => "IdUser e IdClub son obligatorios"
                ]);
            }
        } else {
            // Si no es una solicitud POST
            echo json_encode([
                "success" => false,
                "message" => "Método no permitido"
            ]);
        }
    }

}