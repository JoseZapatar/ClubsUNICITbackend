<?php
include_once '../config/database.php';
include_once '../models/Activities.php';

class ActivitiesController {
    private $db;
    private $activities;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->activities = new Activities(conn: $this->db);
    }

    public function readActivities(): void {
        $stmt = $this->activities->readActivities();
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $activities);
    }

    public function createActivity($data): void {
        $this->activities->description = $data['Description'];
        $this->activities->activityName = $data['ActivityName'];
        $this->activities->activityDate = $data['ActivityDate'];

        if ($this->activities->createActivity()) {
            echo json_encode(value: ["message" => "Actividad creada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear la actividad."]);
        }
    }

    public function updateActivity($data): void {
        $this->activities->idActivities = $data['IdActivities'];
        $this->activities->description = $data['Description'];
        $this->activities->activityName = $data['ActivityName'];
        $this->activities->activityDate = $data['ActivityDate'];

        if ($this->activities->updateActivity()) {
            echo json_encode(value: ["message" => "Actividad actualizada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar la actividad."]);
        }
    }

    public function deleteActivity($id): void {
        $this->activities->idActivities = $id;

        if ($this->activities->deleteActivity()) {
            echo json_encode(value: ["message" => "Actividad eliminada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar la actividad."]);
        }
    }
}
?>
