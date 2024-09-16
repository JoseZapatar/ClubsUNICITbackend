<?php
// Habilitar CORS
// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}

// Verificar si es una solicitud de OPTIONS (preflight request) y devolver una respuesta vacía.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(response_code: 200);
    exit;
}


include_once '../controllers/userControler.php';
include_once '../controllers/rolControler.php';
include_once '../controllers/clubControler.php';
include_once '../controllers/announcementControler.php';
include_once '../controllers/registrationControler.php';
include_once '../controllers/activitiesControler.php';
include_once '../controllers/calendaryControler.php';
include_once '../controllers/authControler.php';

// Obtenemos la URI y los datos del cuerpo de la solicitud
$request = $_SERVER['REQUEST_URI'];
$data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);

switch ($request) {
    case '/user':
        $userController = new UserControler();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $userController->readUsers();
                break;
            case 'POST':
                $userController->createUser($data);
                break;
            case 'PUT':
                $userController->updateUser($data);
                break;
            case 'DELETE':
                if (!empty($data['IdUser'])) {
                    $userController->deleteUser($data['IdUser']);
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


    case '/rol':
        $roleController = new RolControler();
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
        $clubController = new ClubControler();
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
        $announcementController = new AnnouncementControler();
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
        $registrationController = new RegistrationControler();
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
        $activitiesController = new ActivitiesControler();
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
        $calendaryController = new CalendaryControler();
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

    case '/login':
        $authController = new AuthControler();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $authController->login($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;
}
?>