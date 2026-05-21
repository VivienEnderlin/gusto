<?php
require_once __DIR__ . '/../controllers/CommandeController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new CommandeController();
$method = $_SERVER['REQUEST_METHOD'];

// ========================
// Vérification du token
// ========================
$headers = function_exists('getallheaders') ? getallheaders() : [];

if (!isset($headers['authorization'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Token required'
    ]);
    exit;
}

// ========================
// GET
// ========================
if ($method === 'GET') {
    $controller->stat();
    exit;
}

// ========================
// Méthodes non autorisées
// ========================
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Unauthorised method'
]);
exit;
?>