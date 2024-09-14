<?php
include_once '../controllers/registrationControler.php';

$controller = new RegistrationControler();

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($method) {
    case 'GET':
        $controller->readRegistrations();
        break;
    case 'POST':
        $controller->createRegistration(data: $data);
        break;
    case 'PUT':
        $controller->updateRegistration(data: $data);
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteRegistration(id: $id);
        } else {
            echo json_encode(value: ["message" => "ID no proporcionado para eliminar."]);
        }
        break;
    default:
        echo json_encode(value: ["message" => "Método no soportado."]);
        break;
}
