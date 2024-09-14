<?php
include_once '../controllers/userController.php';
include_once '../controllers/rolController.php';
include_once '../controllers/clubController.php';
include_once '../controllers/announcementController.php';
include_once '../controllers/registrationController.php';
include_once '../controllers/activitiesController.php';
include_once '../controllers/calendaryController.php';

$request = $_SERVER['REQUEST_URI'];
$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($request) {
    case '/user':
        $userController = new UserController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $userController->readUsers();
                break;
            case 'POST':
                $userController->createUser(data: $data);
                break;
            case 'PUT':
                $userController->updateUser(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdUser'])) {
                    $userController->deleteUser(id: $data['IdUser']);
                } else {
                    echo json_encode(value: ["message" => "ID del usuario es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/role':
        $roleController = new RolController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $roleController->readRoles();
                break;
            case 'POST':
                $roleController->createRole(data: $data);
                break;
            case 'PUT':
                $roleController->updateRole(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdRol'])) {
                    $roleController->deleteRole(id: $data['IdRol']);
                } else {
                    echo json_encode(value: ["message" => "ID del rol es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/club':
        $clubController = new ClubController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $clubController->readClubs();
                break;
            case 'POST':
                $clubController->createClub(data: $data);
                break;
            case 'PUT':
                $clubController->updateClub(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdClub'])) {
                    $clubController->deleteClub(id: $data['IdClub']);
                } else {
                    echo json_encode(value: ["message" => "ID del club es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/announcement':
        $announcementController = new AnnouncementController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $announcementController->readAnnouncements();
                break;
            case 'POST':
                $announcementController->createAnnouncement(data: $data);
                break;
            case 'PUT':
                $announcementController->updateAnnouncement(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdAnnouncement'])) {
                    $announcementController->deleteAnnouncement(id: $data['IdAnnouncement']);
                } else {
                    echo json_encode(value: ["message" => "ID del anuncio es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(response_code: 405);
                echo json_encode(value: ["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/registration':
        $registrationController = new RegistrationController();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $registrationController->readRegistrations();
                break;
            case 'POST':
                $registrationController->createRegistration(data: $data);
                break;
            case 'PUT':
                $registrationController->updateRegistration(data: $data);
                break;
            case 'DELETE':
                if (!empty($data['IdMatricula'])) {
                    $registrationController->deleteRegistration(id: $data['IdMatricula']);
                } else {
                    echo json_encode(value: ["message" => "ID de la matrícula es necesario para eliminar."]);
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

    default:
        http_response_code(response_code: 404);
        echo json_encode(value: ["message" => "Ruta no encontrada"]);
        break;
}
?>
