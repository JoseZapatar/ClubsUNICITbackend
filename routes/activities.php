<?php
include_once '../controllers/activitiesControler.php';

$controller = new ActivitiesControler();

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($method) {
    case 'GET':
        $controller->readActivities();
        break;
    case 'POST':
        $controller->createActivity();
        break;
    case 'PUT':
        $controller->updateActivity();
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteActivity(id: $id);
        } else {
            echo json_encode(value: ["message" => "ID no proporcionado para eliminar."]);
        }
        break;
    default:
        echo json_encode(value: ["message" => "Método no soportado."]);
        break;
}
