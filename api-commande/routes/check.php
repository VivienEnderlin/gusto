<?php
require_once __DIR__ . '/../core/Cors.php';
require_once __DIR__ . '/../controllers/CommandeController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new CommandeController();
$method = $_SERVER['REQUEST_METHOD'];

// ========================
// GET
// ========================
if ($method === 'GET') {
    $controller->code_verfiy();
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