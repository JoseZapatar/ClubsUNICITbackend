<?php
include_once '../config/database.php';
include_once '../models/User.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class UserControler {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function readUsers(): void {
        $stmt = $this->user->readUsers();
        $users = $stmt;
        echo json_encode($users);
    }

    public function createUser(): void {
        // Imprimir los datos recibidos para depuración
        // Obtener datos del POST
        $user = isset($_POST['user']) ? $_POST['user'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $idRol = isset($_POST['id']) ? $_POST['id'] : null;
    
        // Verificar si se ha subido un archivo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo']['name']; // Nombre del archivo subido
            // Mover el archivo a una carpeta específica si es necesario
            move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $_FILES['photo']['name']);
        } else {
            $photo = null; // O cualquier valor predeterminado si no se subió un archivo
        }
        // Establecer valores en el modelo
        $this->user->user = $user;
        $this->user->password = $password;
        $this->user->email = $email;
        $this->user->picture = $photo; // Asignar el nombre del archivo
        $this->user->idRol = $idRol;
    
        // Crear el usuario
        if ($this->user->createUser()) {
            echo json_encode(["message" => "Usuario creado correctamente."]);
        } else {
            error_log("Error al crear usuario: " . print_r($this->user, true));  // Log the error
            echo json_encode(["message" => "Error al crear usuario."]);
        }
        
    }
    
    

    public function updateUser($data): void {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Imprimir los datos recibidos para depuración
        error_log(print_r($data, true));
        
        $this->user->idUser = $data['IdUser'] ?? null;
        $this->user->user = $data['User'] ?? null;
        $this->user->password = $data['Password'] ?? null;
        $this->user->email = $data['Email'] ?? null;
        $this->user->picture = $data['Picture'] ?? null;
        $this->user->idRol = $data['IdRol'] ?? null;

        if ($this->user->updateUser()) {
            echo json_encode(["message" => "Usuario actualizado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar usuario."]);
        }
    }

    public function deleteUser($id): void {
        $this->user->idUser = $id;

        if ($this->user->deleteUser()) {
            echo json_encode(["message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar usuario."]);
        }
    }

    public function getUserByUsername($username): void {
        $stmt = $this->user->getUserByUsername($username);
        $user = $stmt;
        echo json_encode($user);
    }
    
    

    public function getUserInfo(): void {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            echo json_encode(['error' => 'No authenticated']);
            exit();
        }
    
        // Obtener la información del usuario
        $username = $_SESSION['username'];
        $user = $this->user->getUserByUsername($username); // Suponiendo que tienes este método en el modelo User
    
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }
    
}

