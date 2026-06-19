<?php

require_once __DIR__ . '/../controllers/QrCodeController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$controller = new QrCodeController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID required"]);
        exit;
    }

    $controller->generate($id);
    exit;
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(["error" => "Method not allowed"]);
exit;