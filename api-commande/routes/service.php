<?php
require_once __DIR__ . '/../controllers/ServiceController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new ServiceController();
$method = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();

// Vérification du token
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Token requis']);
    exit;
}

// ========================
// GET
// ========================
if ($method === 'GET') {
    $controller->index();
    exit;
}

// ========================
// Méthodes non autorisées
// ========================
http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
exit;
?>