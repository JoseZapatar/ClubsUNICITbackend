<?php

include_once '../controllers/rolControler.php'; 


$rolController = new RolControler();


$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];


switch ($requestUri) {
    case '/roles':
        if ($requestMethod === 'GET') {
            $rolController->readRoles();
        } elseif ($requestMethod === 'POST') {
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);
            $rolController->createRole(data: $data);
        }
        break;

    case (preg_match(pattern: '/\/roles\/(\d+)/', subject: $requestUri, matches: $matches) ? true : false):
        $id = $matches[1];
        if ($requestMethod === 'PUT') {
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);
            $rolController->updateRole(data: $data);
        } elseif ($requestMethod === 'DELETE') {
            $rolController->deleteRole(id: $id);
        }
        break;

    default:
        http_response_code(response_code: 404);
        echo json_encode(value: ["message" => "Ruta no encontrada."]);
        break;
}

