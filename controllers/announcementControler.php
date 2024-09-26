<?php
include_once '../config/database.php';
include_once '../models/Announcement.php';

class AnnouncementControler
{
    private $db;
    private $announcement;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->announcement = new Announcement(conn: $this->db);
    }

    public function readAnnouncements(): void
    {
        $stmt = $this->announcement->readAnnouncements();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $announcements);
    }

    public function createAnnouncement(): void
    {
        // Deshabilitar cualquier salida innecesaria
        ob_start();

        $this->announcement->description = $_POST['description'] ?? '';
        $this->announcement->name = $_POST['tituloAnuncio'] ?? '';
        $clubId = $_POST['clubId'] ?? null;

        // Manejo del archivo
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDirectory = 'uploads/';
            $fileName = basename($_FILES['picture']['name']);
            $uploadFilePath = $uploadDirectory . $fileName;

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFilePath)) {
                $this->announcement->picture = $uploadFilePath;
            } else {
                echo json_encode(["message" => "Error al mover el archivo."]);
                return;
            }
        } else {
            $this->announcement->picture = null;
        }

        // Crear el anuncio y obtener su id
        if ($this->announcement->createAnnouncement()) {
            $announcementId = $this->db->lastInsertId(); // Obtener el id del último anuncio insertado

            // Insertar en la tabla pivote
            if ($clubId !== null) {
                $this->insertIntoPivotTable($clubId, $announcementId);
            }

            // Limpiar el buffer de salida para asegurarnos de que no haya caracteres extra
            ob_clean();

            echo json_encode([
                "message" => "Anuncio creado correctamente.",
                "idClub" => $clubId,
                "idAnnouncement" => $announcementId
            ]);
        } else {
            // Limpiar el buffer de salida para asegurarnos de que no haya caracteres extra
            ob_clean();

            echo json_encode(["message" => "Error al crear anuncio."]);
        }
    }



    // Función para insertar en la tabla pivote
    private function insertIntoPivotTable($clubId, $announcementId): void
    {
        // Debug: Verificar los valores antes de ejecutar la consulta
        echo json_encode([
            "debug_message" => "Insertando en la tabla pivote.",
            "idClub" => $clubId,
            "idAnnouncement" => $announcementId
        ]);

        $query = "INSERT INTO club_announcement (IdClub, IdAnnouncement) VALUES (:clubId, :announcementId)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':clubId', $clubId, PDO::PARAM_INT);
        $stmt->bindParam(':announcementId', $announcementId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Club vinculado al anuncio correctamente."]);
        } else {
            echo json_encode(["message" => "Error al vincular club con anuncio."]);
        }
    }




    public function updateAnnouncement($data): void
    {
        $this->announcement->idAnnouncement = $data['IdAnnouncement'];
        $this->announcement->description = $data['Description'];
        $this->announcement->picture = $data['Picture'];
        $this->announcement->name = $data['Name'];

        if ($this->announcement->updateAnnouncement()) {
            echo json_encode(value: ["message" => "Anuncio actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar anuncio."]);
        }
    }

    public function deleteAnnouncement($id): void
    {
        $this->announcement->idAnnouncement = $id;

        if ($this->announcement->deleteAnnouncement()) {
            echo json_encode(value: ["message" => "Anuncio eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar anuncio."]);
        }
    }
}
