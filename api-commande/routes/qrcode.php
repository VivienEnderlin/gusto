<?php
require_once __DIR__ . '/../controllers/QrCodeController.php';

$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();

// ========================
// Vérification token
// ========================
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode([
        'success'=>false,
        'message'=>'Token requis'
    ]);
    exit;
}

$controller = new QrCodeController();

// ========================
// GET
// ========================
if ($method === 'GET') {
    $controller->generate();
    exit;
}

http_response_code(405);
echo json_encode([
    'success'=>false,
    'message'=>'Méthode non autorisée'
]);