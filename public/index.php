<?php
// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];

// Verificar si es una solicitud de OPTIONS y devolver una respuesta vacía
if ($method == "OPTIONS") {
    http_response_code(200);
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
$data = json_decode(file_get_contents("php://input"), true);

// Cambiamos la estructura de las rutas
switch ($request) {
    case '/user':
        $userController = new UserControler();
        switch ($method) {
            case 'GET':
                $userController->readUsers();
                break;
            case 'POST':
                // Usamos $_POST y $_FILES para el caso de formularios con archivos
                $userController->createUser($data);
                break;
            case 'PUT':
                $userController->updateUser($data);
                break;
            case 'DELETE':
                if (!empty($data['IdUser'])) {
                    $userController->deleteUser($data['IdUser']);
                } else {
                    echo json_encode(["message" => "ID del usuario es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/rol':
        $roleController = new RolControler();
        switch ($method) {
            case 'GET':
                $roleController->readRoles();
                break;
            case 'POST':
                $roleController->createRole($data);
                break;
            case 'PUT':
                $roleController->updateRole($data);
                break;
            case 'DELETE':
                if (!empty($data['IdRol'])) {
                    $roleController->deleteRole($data['IdRol']);
                } else {
                    echo json_encode(["message" => "ID del rol es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/club':
        $clubController = new ClubControler();
        switch ($method) {
            case 'GET':
                $clubController->readClubs();
                break;
            case 'POST':
                $clubController->createClub($data);
                break;
            case 'PUT':
                $clubController->updateClub($data);
                break;
            case 'DELETE':
                if (!empty($data['IdClub'])) {
                    $clubController->deleteClub($data['IdClub']);
                } else {
                    echo json_encode(["message" => "ID del club es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/announcement':
        $announcementController = new AnnouncementControler();
        switch ($method) {
            case 'GET':
                $announcementController->readAnnouncements();
                break;
            case 'POST':
                $announcementController->createAnnouncement($data);
                break;
            case 'PUT':
                $announcementController->updateAnnouncement($data);
                break;
            case 'DELETE':
                if (!empty($data['IdAnnouncement'])) {
                    $announcementController->deleteAnnouncement($data['IdAnnouncement']);
                } else {
                    echo json_encode(["message" => "ID del anuncio es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/registration':
        $registrationController = new RegistrationControler();
        switch ($method) {
            case 'GET':
                $registrationController->readRegistrations();
                break;
            case 'POST':
                $registrationController->createRegistration($data);
                break;
            case 'PUT':
                $registrationController->updateRegistration($data);
                break;
            case 'DELETE':
                if (!empty($data['IdMatricula'])) {
                    $registrationController->deleteRegistration($data['IdMatricula']);
                } else {
                    echo json_encode(["message" => "ID de la matrícula es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/activities':
        $activitiesController = new ActivitiesControler();
        switch ($method) {
            case 'GET':
                $activitiesController->readActivities();
                break;
            case 'POST':
                $activitiesController->createActivity($data);
                break;
            case 'PUT':
                $activitiesController->updateActivity($data);
                break;
            case 'DELETE':
                if (!empty($data['IdActivities'])) {
                    $activitiesController->deleteActivity($data['IdActivities']);
                } else {
                    echo json_encode(["message" => "ID de la actividad es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/calendary':
        $calendaryController = new CalendaryControler();
        switch ($method) {
            case 'GET':
                $calendaryController->readCalendaries();
                break;
            case 'POST':
                $calendaryController->createCalendary($data);
                break;
            case 'PUT':
                $calendaryController->updateCalendary($data);
                break;
            case 'DELETE':
                if (!empty($data['IdCalendary'])) {
                    $calendaryController->deleteCalendary($data['IdCalendary']);
                } else {
                    echo json_encode(["message" => "ID del calendario es necesario para eliminar."]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/login':
        $authController = new AuthControler();
        switch ($method) {
            case 'POST':
                $authController->login($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Ruta no encontrada"]);
        break;
}
?>