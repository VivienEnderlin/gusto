<?php
require_once __DIR__ . '/../controllers/CommandeController.php';
require_once __DIR__ . '/../core/Middleware.php';


header('Content-Type: application/json; charset=utf-8');
$user = Middleware::checkAuth();

$controller = new CommandeController();
$method = $_SERVER['REQUEST_METHOD'];

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