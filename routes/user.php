<?php

include_once '../controllers/userController.php';

$userController = new UserController();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$id = null;
$matches = [];


if (preg_match(pattern: '/^\/user\/(\d+)$/', subject: $requestUri, matches: $matches)) {
    $id = $matches[1];
}

switch (true) {
    
    case ($requestUri === '/user' && $requestMethod === 'GET'):
        $userController->readUsers();
        break;

    
    case ($requestUri === '/user' && $requestMethod === 'POST'):
        $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);
        $userController->createUser(data: $data);
        break;

    
    case ($id !== null && $requestMethod === 'PUT'):
        $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);
        $userController->updateUser(data: $data);
        break;

    
    case ($id !== null && $requestMethod === 'DELETE'):
        $userController->deleteUser(id: $id);
        break;

    
    default:
        http_response_code(response_code: 404);
        echo json_encode(value: ["message" => "Ruta no encontrada."]);
        break;
}
