<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once '../core/Database.php';
require_once '../core/Config.php';

require_once '../repositories/UserRepository.php';
require_once '../services/UserService.php';
require_once '../controllers/UserController.php';

require_once '../repositories/RoomRepository.php';
require_once '../services/RoomService.php';
require_once '../controllers/RoomController.php';

require_once '../repositories/BookingRepository.php';
require_once '../services/BookingService.php';
require_once '../controllers/BookingController.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$db = (new Database())->getConnection();

$userRepo    = new UserRepository($db);
$roomRepo    = new RoomRepository($db);
$bookingRepo = new BookingRepository($db);

$userService = new UserService($userRepo);
$roomService = new RoomService($roomRepo);
$bookingService = new BookingService(
    $bookingRepo,
    $userRepo,
    $roomRepo
);

$userController    = new UserController($userService);
$roomController    = new RoomController($roomService);
$bookingController = new BookingController($bookingService);

// auto release rooms
$bookingService->autoReleaseRooms();

// routing handler
$method = $_SERVER['REQUEST_METHOD'];
$path   = $_GET['path'] ?? '';

// user routes
if ($method === 'GET' && $path === 'users') {
    $userController->index();
} elseif ($method === 'GET' && $path === 'users/show') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID user wajib disertakan'
        ]);
        exit;
    }
    $userController->show((int)$_GET['id']);
} elseif ($method === 'POST' && $path === 'users') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userController->store($data);

    // room routes
} elseif ($method === 'GET' && $path === 'rooms') {
    $roomController->index();
} elseif ($method === 'GET' && $path === 'rooms/show') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID ruangan wajib disertakan'
        ]);
        exit;
    }
    $roomController->show((int)$_GET['id']);
} elseif ($method === 'POST' && $path === 'rooms') {
    $data = json_decode(file_get_contents('php://input'), true);
    $roomController->store($data);

    // booking user routes
} elseif ($method === 'POST' && $path === 'bookings') {
    $data = json_decode(file_get_contents('php://input'), true);
    $bookingController->store($data);
} elseif ($method === 'GET' && $path === 'bookings/my') {
    if (!isset($_GET['user_id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'user_id wajib disertakan'
        ]);
        exit;
    }

    $status = $_GET['status'] ?? null;
    $bookingController->myBookings(
        (int)$_GET['user_id'],
        $status
    );
} elseif ($method === 'GET' && $path === 'bookings/history') {
    if (!isset($_GET['user_id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'user_id wajib disertakan'
        ]);
        exit;
    }

    $bookingController->history(
        (int)$_GET['user_id']
    );

    // booking admin routes
} elseif ($method === 'GET' && $path === 'bookings') {
    $status = $_GET['status'] ?? null;
    $bookingController->index($status);
} elseif ($method === 'POST' && $path === 'bookings/approve') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID booking wajib disertakan'
        ]);
        exit;
    }
    $bookingController->approve((int)$_GET['id']);
} elseif ($method === 'POST' && $path === 'bookings/reject') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID booking wajib disertakan'
        ]);
        exit;
    }
    $bookingController->reject((int)$_GET['id']);

    // fallback for undefined routes
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan'
    ]);
}
