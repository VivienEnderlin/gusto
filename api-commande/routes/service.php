<?php
require_once __DIR__ . '/../controllers/ServiceController.php';
require_once __DIR__ . '/../core/Middleware.php';


header('Content-Type: application/json; charset=utf-8');
$user = Middleware::checkAuth();

$controller = new ServiceController();
$method = $_SERVER['REQUEST_METHOD'];


// ========================
// GET
// ========================
if ($method === 'GET') {
    $id = isset($_GET['id_table']) ? (int) $_GET['id_table'] : null;

    if ($id) {
        $controller->show($id);
    } else {
        $controller->index();
    }
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