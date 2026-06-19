<?php

require_once __DIR__ . '/../controllers/QrCodeController.php';
require_once __DIR__ . '/../core/Middleware.php';

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

$controller = new QrCodeController();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "ID required"
        ]);
        exit;
    }

    $controller->generate($id);
    exit;
}

http_response_code(405);
echo json_encode([
    "success" => false,
    "message" => "Method not allowed"
]);
exit;