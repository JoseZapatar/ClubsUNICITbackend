<?php

include_once '../controllers/AnnouncementControler.php';

$controller = new AnnouncementControler();

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($method) {
    case 'GET':
        $controller->readAnnouncements();
        break;
    case 'POST':
        $controller->createAnnouncement(data: $data);
        break;
    case 'PUT':
        $controller->updateAnnouncement(data: $data);
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteAnnouncement(id: $id);
        } else {
            echo json_encode(value: ["message" => "ID no proporcionado para eliminar."]);
        }
        break;
    default:
        echo json_encode(value: ["message" => "Método no soportado."]);
        break;
}
