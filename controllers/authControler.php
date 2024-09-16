<?php
// Incluir la clase de conexión a la base de datos
include_once '../config/database.php';

class AuthControler
{
    private $conn;

    // Constructor que establece la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para verificar el login
    public function login($data)
    {
        // Definir el encabezado de respuesta como JSON
        header("Content-Type: application/json; charset=UTF-8");

        // Verificar que los campos User y Password estén presentes en los datos recibidos
        if (isset($data['User']) && isset($data['Password'])) {
            $username = $data['User'];
            $password = $data['Password'];

            try {
                // Consulta SQL para buscar al usuario
                $query = "SELECT * FROM user WHERE User = :username";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':username', $username);

                // Ejecutar la consulta
                $stmt->execute();

                // Verificar si encontramos al usuario
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Comparar la contraseña proporcionada con la almacenada
                    if (password_verify($password, $user['Password'])) {
                        // Si es válida, retornamos un mensaje de éxito y detalles del usuario
                        echo json_encode([
                            "success" => true,
                            "message" => "Login exitoso",
                            "user" => [
                                "username" => $user['User'],
                                "email" => $user['Email'],
                                "idRol" => $user['IdRol']
                            ]
                        ]);
                    } else {
                        // Si la contraseña no es válida
                        echo json_encode([
                            "success" => false,
                            "message" => "Contraseña incorrecta"
                        ]);
                    }
                } else {
                    // Si el usuario no existe
                    echo json_encode([
                        "success" => false,
                        "message" => "Usuario no encontrado"
                    ]);
                }
            } catch (PDOException $e) {
                // Manejar errores de base de datos
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
            }
        } else {
            // Si no se proporcionan el nombre de usuario o la contraseña
            echo json_encode([
                "success" => false,
                "message" => "Por favor, proporcione un nombre de usuario y una contraseña"
            ]);
        }
    }
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear instancia del controlador de autenticación
    $authController = new AuthControler();

    // Obtener los datos enviados en el cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Llamar al método login con los datos proporcionados
    $authController->login($data);
} else {
    // Manejar error si no es una solicitud POST
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido, utilice POST"
    ]);
}
