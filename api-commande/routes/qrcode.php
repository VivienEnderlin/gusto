<?php
require_once __DIR__ . '/../controllers/QrCodeController.php';
require_once __DIR__ . '/../core/Middleware.php';
header('Content-Type: application/json; charset=utf-8');

$user = Middleware::checkAuth();

$method = $_SERVER['REQUEST_METHOD'];

// ========================
// Vérification token
// ========================

$controller = new QrCodeController();

// ========================
// GET : générer QR code
// ========================
if ($method === 'GET') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID required'
        ]);
        exit;
    }

    $controller->generate($id);
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