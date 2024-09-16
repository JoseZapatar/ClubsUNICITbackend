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
        // Verificar si los datos no están vacíos
        if (empty($data)) {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode([
                "success" => false,
                "message" => "No se enviaron datos"
            ]);
            exit();
        }

        // Definir el encabezado de respuesta como JSON
        header("Content-Type: application/json; charset=UTF-8");

        // Verificar que los campos User y Password estén presentes en los datos recibidos
        if (isset($data['User']) && isset($data['Password'])) {
            $username = $data['User'];
            $password = $data['Password'];  // Contraseña ingresada por el usuario (sin hash)

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

                    // Comparar directamente la contraseña en texto plano
                    if ($password === $user['Password']) {  // Comparación directa
                        // Si la contraseña es correcta
                        echo json_encode([
                            "success" => true,
                            "message" => "Login exitoso",
                            "user" => [
                                "username" => $user['User'],
                                "email" => $user['Email'],
                                "idRol" => $user['IdRol']
                            ]
                        ]);
                        exit(); // Terminar la ejecución aquí
                    } else {
                        // Si la contraseña no coincide
                        echo json_encode([
                            "success" => false,
                            "message" => "Contraseña incorrecta"
                        ]);
                        exit(); // Terminar la ejecución aquí
                    }
                } else {
                    // Si el usuario no existe
                    echo json_encode([
                        "success" => false,
                        "message" => "Usuario no encontrado"
                    ]);
                    exit(); // Terminar la ejecución aquí
                }
            } catch (PDOException $e) {
                // Manejar errores de base de datos
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
                exit(); // Terminar la ejecución aquí
            }
        } else {
            // Si no se proporcionan el nombre de usuario o la contraseña
            echo json_encode([
                "success" => false,
                "message" => "Por favor, proporcione un nombre de usuario y una contraseña"
            ]);
            exit(); // Terminar la ejecución aquí
        }
    }
}
