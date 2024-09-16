<?php
class Activities {
    private $conn;
    public $idActivities;
    public $description;
    public $activityName;
    public $activityDate;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para leer las actividades
    public function readActivities(): mixed {
        $sql = "SELECT * FROM activities";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear una actividad
    public function createActivity(): mixed {
        $sql = "INSERT INTO activities (Description, ActivityName, ActivityDate) 
                VALUES (:description, :activityName, :activityDate)";
        $stmt = $this->conn->prepare($sql);

        // Usar los valores de $_POST para los parámetros
        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':activityName', $_POST['tituloEvento']);
        $stmt->bindParam(':activityDate', $_POST['eventDate']);
        
        return $stmt->execute();
    }

    // Método para actualizar una actividad
    public function updateActivity(): mixed {
        $sql = "UPDATE activities SET Description = :description, ActivityName = :activityName, 
                ActivityDate = :activityDate WHERE IdActivities = :idActivities";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':description', $_POST['description']);
        $stmt->bindParam(':activityName', $_POST['tituloEvento']);
        $stmt->bindParam(':activityDate', $_POST['eventDate']);
        $stmt->bindParam(':idActivities', $_POST['idActivities'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Método para eliminar una actividad
    public function deleteActivity(): mixed {
        $sql = "DELETE FROM activities WHERE IdActivities = :idActivities";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idActivities', $_POST['idActivities'], PDO::PARAM_INT);
        return $stmt->execute();
    }
}
