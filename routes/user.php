<?php
include_once '../controllers/userController.php';

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->readUsers();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);
    $controller->createUser(data: $data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);
    $controller->updateUser(data: $data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    $controller->deleteUser(id: $id);
}
?>
