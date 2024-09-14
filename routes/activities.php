<?php
include_once '../controllers/activitiesController.php';
include_once '../controllers/calendaryController.php';

$request = $_SERVER['REQUEST_URI'];
$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($request) {
    case '/calendary':
        $calendaryController = new CalendaryController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $calendaryController->readCalendaries();
                break;
            case 'POST':
                $calendaryController->createCalendary(data: $data);
                break;
            case 'PUT':
                $calendaryController->updateCalendary(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdCalendary'])) {
                    $calendaryController->deleteCalendary(id: $data['IdCalendary']);
                } else {
                    echo json_encode(value: ["message" => "ID del calendario es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;
        
    case '/activities':
        $activitiesController = new ActivitiesController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $activitiesController->readActivities();
                break;
            case 'POST':
                $activitiesController->createActivity(data: $data);
                break;
            case 'PUT':
                $activitiesController->updateActivity(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdActivities'])) {
                    $activitiesController->deleteActivity(id: $data['IdActivities']);
                } else {
                    echo json_encode(value: ["message" => "ID de la actividad es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;

    default:
        http_response_code(response_code: 404);
        echo json_encode(value: ["message" => "Ruta no encontrada"]);
        break;
}
?>
