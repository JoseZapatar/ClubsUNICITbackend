<?php
include_once '../controllers/calendaryController.php';

$controller = new CalendaryController();

// Analizar el cuerpo de la solicitud JSON para los métodos POST, PUT y DELETE
$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

// Definir las rutas específicas para las operaciones CRUD
switch ($_SERVER['REQUEST_METHOD']) {
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
        // Asegúrate de que el ID esté presente para borrar el calendario
        if (!empty($data['IdCalendary'])) {
            $controller->deleteCalendary(id: $data['IdCalendary']);
        } else {
            echo json_encode(value: ["message" => "ID del calendario es necesario para eliminar."]);
        }
        break;
    default:
        http_response_code(response_code: 405);
        echo json_encode(value: ["message" => "Método no permitido"]);
        break;
}
?>
