<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");


include_once '../config/database.php';

class AuthControler
{
    private $conn;

    public function __construct()
    {
        // Iniciar la sesión si no se ha iniciado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($data)
    {
        if (empty($data)) {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode([
                "success" => false,
                "message" => "No se enviaron datos"
            ]);
            exit();
        }

        if (isset($data['User']) && isset($data['Password'])) {
            $username = $data['User'];
            $password = $data['Password'];

            try {
                $query = "SELECT * FROM user WHERE User = :username";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($password === $user['Password']) {
                        $_SESSION['authenticated'] = true;
                        $_SESSION['username'] = $user['User'];
                        $_SESSION['email'] = $user['Email'];
                        $_SESSION['IdUser'] = $user['IdUser'];
                        $_SESSION['idRol'] = $user['IdRol'];

                        echo json_encode([
                            "success" => true,
                            "message" => "Login exitoso"
                        ]);
                        exit();
                    } else {
                        echo json_encode([
                            "success" => false,
                            "message" => "Contraseña incorrecta"
                        ]);
                        exit();
                    }
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Usuario no encontrado"
                    ]);
                    exit();
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "message" => "Error en la consulta: " . $e->getMessage()
                ]);
                exit();
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Por favor, proporcione un nombre de usuario y una contraseña"
            ]);
            exit();
        }
    }

    public function checkAuth()
    {
        header("Content-Type: application/json; charset=UTF-8");

        // Mostrar el valor de la sesión para depuración
        error_log('Session Data: ' . print_r($_SESSION, true));

        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            $username = $_SESSION['username'];
            $userData = $this->getUserData($username);

            if ($userData) {
                echo json_encode([
                    "authenticated" => true,
                    'IdUser' => $userData['IdUser'],
                    "username" => $userData['User'],
                    "email" => $userData['Email'],
                    "picture" => $userData['Picture']
                ]);
            } else {
                echo json_encode([
                    "authenticated" => false
                ]);
            }
        } else {
            echo json_encode([
                "authenticated" => false
            ]);
        }
        exit();
    }

    private function getUserData($username)
    {
        $query = "SELECT IdUser, User, Email, Picture FROM user WHERE User = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function logout()
    {
        // Cierra sesión
        session_unset();
        session_destroy();
    
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200); // Opcional: establece el código de estado
        echo json_encode([
            "success" => true,
            "message" => "Logout exitoso"
        ]);
        exit();
    }
    
}
