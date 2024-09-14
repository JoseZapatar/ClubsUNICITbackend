<?php

include_once '../controllers/clubControler.php';

$controller = new ClubControler();

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($method) {
    case 'GET':
        
        $controller->readClubs();
        break;
    case 'POST':
        
        $controller->createClub(data: $data);
        break;
    case 'PUT':
       
        $controller->updateClub(data: $data);
        break;
    case 'DELETE':
       
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->deleteClub(id: $id);
        } else {
            echo json_encode(value: ["message" => "ID no proporcionado para eliminar."]);
        }
        break;
    default:
        echo json_encode(value: ["message" => "Método no soportado."]);
        break;
}
