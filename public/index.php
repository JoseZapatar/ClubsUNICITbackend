<?php
// archivo index.php o api.php

// Habilitar CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Iniciar sesión
session_start();

// Definir el tipo de contenido como JSON
header("Content-Type: application/json; charset=UTF-8");

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Verificar si es una solicitud de OPTIONS y devolver una respuesta vacía
if ($method == "OPTIONS") {
    http_response_code(200);
    exit;
}

// Incluir controladores
include_once '../controllers/userControler.php';
include_once '../controllers/rolControler.php';
include_once '../controllers/clubControler.php';
include_once '../controllers/announcementControler.php';
include_once '../controllers/registrationControler.php';
include_once '../controllers/activitiesControler.php';
include_once '../controllers/calendaryControler.php';
include_once '../controllers/authControler.php';
include_once '../controllers/userClubControler.php';

// Obtenemos la URI y los datos del cuerpo de la solicitud
$request = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents("php://input"), true);

// Extraer la ruta de la solicitud
$requestPath = strtok($request, '?'); // Elimina los parámetros de consulta

// Rutas
switch ($requestPath) {
    // Rutas para usuario
    case '/user':
        $userController = new UserControler();
        switch ($method) {
            case 'GET':
                $userController->readUsers();
                break;
            case 'POST':
                $userController->createUser();
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

    // Rutas para roles
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

    // Rutas para clubs
    case '/club':
        $clubController = new ClubControler();
        switch ($method) {
            case 'GET':
                $clubController->readClubs();
                break;
            case 'POST':
                $clubController->createClub();
                break;
            case 'PUT':
                $clubController->updateClub();
                break;
            case 'DELETE':
                if (!empty($data['IdClub'])) {
                    $clubController->deleteClub();
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

    // Rutas para clubes
    case '/club/search':
        $clubController = new ClubControler();
        switch ($method) {
            case 'GET':
                error_log("Término de búsqueda: " . (isset($_GET['term']) ? $_GET['term'] : 'no definido'));
                $searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
                $clubController->searchClubs($searchTerm);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    // Rutas para anuncios
    case '/announcement':
        $announcementController = new AnnouncementControler();
        switch ($method) {
            case 'GET':
                $announcementController->readAnnouncements();
                break;
            case 'POST':
                $announcementController->createAnnouncement();
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

    // Rutas para registros
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

    // Rutas para actividades
    case '/activities':
        $activitiesController = new ActivitiesControler();
        switch ($method) {
            case 'GET':
                $activitiesController->readActivities();
                break;
            case 'POST':
                $activitiesController->createActivity();
                break;
            case 'PUT':
                $activitiesController->updateActivity();
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

    // Rutas para calendarios
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

    // Ruta para login
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

    // Ruta para verificar autenticación
    case '/check-auth':
        $authControler = new AuthControler();
        switch ($method) {
            case 'GET':
                $authControler->checkAuth();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    // Ruta para logout
    case '/logout':
        $authController = new AuthControler();
        if ($method === 'POST') {
            $authController->logout();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    // Rutas para user-clubs
    case '/user-clubs':
        $userClubController = new UserClubControler();
        switch ($method) {
            case 'GET':
                $userClubController->getUserClubs();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/user-club/register':
        $userClubController = new UserClubControler();
        switch ($method) {
            case 'POST':
                $userClubController->registerUserClub($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/posts':
        $userClubController = new UserClubControler();
        switch ($method) {
            case 'GET':
                $userClubController->getUserAnnouncements();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    // Nueva ruta para obtener los clubes y actividades del usuario
    case '/user-clubs-activities':
        $activitiesController = new ActivitiesControler();
        switch ($method) {
            case 'GET':
                $activitiesController->getUserActivities();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    default:
        error_log("Ruta solicitada: " . $request);
        var_dump("Ruta solicitada: " . $request); // Agrega esta línea para depuración
        http_response_code(404);
        echo json_encode(["message" => "Ruta no encontrada"]);
        break;
}
