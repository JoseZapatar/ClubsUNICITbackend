<?php
include_once '../config/database.php';
include_once '../models/User.php';

// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
class UserControler
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function readUsers(): void
    {
        $stmt = $this->user->readUsers();
        $users = $stmt;
        echo json_encode($users);
    }

    public function createUser(): void
    {
        // Obtener datos del POST
        $user = isset($_POST['user']) ? $_POST['user'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $idRol = isset($_POST['id']) ? $_POST['id'] : null;

        // Directorio donde se almacenarán las imágenes
        $uploadDir = 'uploads/';

        // Crear el directorio si no existe y establecer permisos
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Verificar si se ha subido una imagen y moverla al directorio
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $uploadDir . basename($_FILES['photo']['name']);
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                // Guardar la ruta de la imagen en el modelo
                $this->user->picture = $photoPath;
            } else {
                // Si no se pudo mover el archivo, establecer picture como null
                $this->user->picture = null;
            }
        } else {
            // Si no se subió archivo, establecer picture como null
            $this->user->picture = null;
        }

        // Asignar los demás valores al modelo
        $this->user->user = $user;
        $this->user->password = $password;
        $this->user->email = $email;
        $this->user->idRol = $idRol;

        // Crear el usuario
        if ($this->user->createUser()) {
            echo json_encode(["message" => "Usuario creado correctamente."]);
        } else {
            // En caso de error, registrar en el log
            error_log("Error al crear usuario: " . print_r($this->user, true));
            echo json_encode(["message" => "Error al crear usuario."]);
        }
    }
    public function updateUser(): void
    {
        // Verificar que la sesión tenga un ID de usuario
        if (!isset($_SESSION["IdUser"])) {
            echo json_encode(["error" => "Usuario no autenticado."]);
            return;
        }

        // Capturar datos
        $idUser = $_SESSION["IdUser"];
        $userName = isset($_POST['username']) ? $_POST['username'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $picture = isset($_FILES['profilePicture']) ? $_FILES['profilePicture'] : null;

        // Depurar los valores individuales
        error_log("ID de Usuario: $idUser");
        error_log("Nombre de Usuario: $userName");
        error_log("Email: $email");
        error_log("Imagen: " . ($picture ? $picture['name'] : 'No hay imagen'));

        // Validar campos obligatorios
        if (!$userName || !$email) {
            echo json_encode([
                "error" => "Faltan datos obligatorios",
                "IdUser" => $idUser,
                "User" => $userName,
                "Email" => $email,
                "Picture" => ($picture ? $picture['name'] : 'No hay imagen')
            ]);
            return;
        }

        // Establecer valores en el modelo
        $this->user->idUser = $idUser;
        $this->user->user = $userName;
        $this->user->email = $email;
        $this->user->password = $password;

        // Procesar la imagen
        if ($picture) {
            $uploadDir = 'uploads/'; // Ruta donde se guardarán las fotos
            $uploadFilePath = $uploadDir . basename($picture['name']);

            // Mover el archivo subido a la carpeta 'uploads/'
            if (move_uploaded_file($picture['tmp_name'], $uploadFilePath)) {
                // Guardar la ruta en el modelo
                $this->user->picture = $uploadFilePath;
            } else {
                echo json_encode(["error" => "Error al mover el archivo."]);
                return;
            }
        } else {
            $this->user->picture = null; // No se actualiza si no se envió un nuevo archivo
        }

        // Actualizar el usuario
        if ($this->user->updateUser()) {
            echo json_encode([
                "message" => "Usuario actualizado correctamente.",
                "IdUser" => $idUser,
                "User" => $userName,
                "Email" => $email,
                "Picture" => ($picture ? $picture['name'] : 'No hay imagen')
            ]);
        } else {
            echo json_encode(["error" => "Error al actualizar usuario."]);
        }
    }


    public function deleteUser($id): void
    {
        $this->user->idUser = $id;

        if ($this->user->deleteUser()) {
            echo json_encode(["message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar usuario."]);
        }
    }

    public function getUserByUsername($username): void
    {
        $stmt = $this->user->getUserByUsername($username);
        $user = $stmt;
        echo json_encode($user);
    }



    public function getUserInfo(): void
    {
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

