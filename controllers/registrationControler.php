<?php
include_once '../config/database.php';
include_once '../models/Registration.php';

class RegistrationControler {
    private $db;
    private $registration;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->registration = new Registration(conn: $this->db);
    }

    public function readRegistrations(): void {
        $stmt = $this->registration->readRegistrations();
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(value: $registrations);
    }

    public function createRegistration($data): void {
        $this->registration->idClub = $data['IdClub'];
        $this->registration->idUser = $data['IdUser'];

        if($this->registration->createRegistration()) {
            echo json_encode(value: ["message" => "Matriculación creada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al crear matriculación."]);
        }
    }

    public function updateRegistration($data): void {
        $this->registration->idMatricula = $data['IdMatricula'];
        $this->registration->idClub = $data['IdClub'];
        $this->registration->idUser = $data['IdUser'];

        if($this->registration->updateRegistration()) {
            echo json_encode(value: ["message" => "Matriculación actualizada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al actualizar matriculación."]);
        }
    }

    public function deleteRegistration($id): void {
        $this->registration->idMatricula = $id;

        if($this->registration->deleteRegistration()) {
            echo json_encode(value: ["message" => "Matriculación eliminada correctamente."]);
        } else {
            echo json_encode(value: ["message" => "Error al eliminar matriculación."]);
        }
    }
}
