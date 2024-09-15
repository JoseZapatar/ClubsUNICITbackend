<?php
class AuthControler {
    private $conn;

    // Constructor que establece la conexión con la base de datos
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para verificar el login
    public function login($data) {
        // Verificamos que los campos User y Password estén presentes en los datos recibidos
        if (isset($data['User']) && isset($data['Password'])) {
            $username = $data['User'];
            $password = $data['Password'];

            // Consulta SQL para buscar al usuario
            $query = "SELECT * FROM User WHERE User = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificamos si encontramos al usuario
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Comparamos la contraseña proporcionada con la almacenada
                if (password_verify($password, $user['Password'])) {
                    // Si es válida, retornamos un mensaje de éxito
                    echo json_encode([
                        "message" => "Login exitoso",
                        "user" => $user['User'],
                        "email" => $user['Email'],
                        "idRol" => $user['IdRol']
                    ]);
                } else {
                    // Si la contraseña no es válida
                    echo json_encode(["message" => "Contraseña incorrecta"]);
                }
            } else {
                // Si el usuario no existe
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            // Si no se proporcionan el nombre de usuario o la contraseña
            echo json_encode(["message" => "Por favor, proporcione un nombre de usuario y una contraseña"]);
        }
    }
}
