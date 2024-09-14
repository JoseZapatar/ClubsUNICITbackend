<?php

include_once '../controllers/calendaryControler.php';

$controller = new CalendaryControler();

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($method) {
    case 'GET':
        $controller->readCalendaries();
        break;
    case 'POST':
        $controller->createCalendary(data: $data);
        break;
    case 'PUT':
        $controller->updateCalendary(data: $data);
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteCalendary(id: $id);
        } else {
            echo json_encode(value: ["message" => "ID no proporcionado para eliminar."]);
        }
        break;
    default:
        echo json_encode(value: ["message" => "Método no soportado."]);
        break;
}
