<?php
include_once '../config/database.php';
include_once '../models/Calendary.php';

class CalendaryController {
    private $db;
    private $calendary;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->calendary = new Calendary(conn: $this->db);
    }

    public function readCalendaries(): void {
        $stmt = $this->calendary->readCalendaries();
        $calendaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $calendaries);
    }

    public function createCalendary($data): void {
        $this->calendary->name = $data['Name'];
        $this->calendary->idActivities = $data['IdActivities'];

        if ($this->calendary->createCalendary()) {
            echo json_encode(value: ["message" => "Calendario creado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear el calendario."]);
        }
    }

    public function updateCalendary($data): void {
        $this->calendary->idCalendary = $data['IdCalendary'];
        $this->calendary->name = $data['Name'];
        $this->calendary->idActivities = $data['IdActivities'];

        if ($this->calendary->updateCalendary()) {
            echo json_encode(value: ["message" => "Calendario actualizado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar el calendario."]);
        }
    }

    public function deleteCalendary($id): void {
        $this->calendary->idCalendary = $id;

        if ($this->calendary->deleteCalendary()) {
            echo json_encode(value: ["message" => "Calendario eliminado correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar el calendario."]);
        }
    }
}
