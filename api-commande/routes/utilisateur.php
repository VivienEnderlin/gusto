<?php
require_once __DIR__ . '/../controllers/UtilisateurController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new UtilisateurController();
$method = $_SERVER['REQUEST_METHOD'];

// ========================
// Vérification du token
// ========================
$headers = function_exists('getallheaders') ? getallheaders() : [];

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Token required'
    ]);
    exit;
}

// ========================
// Lire JSON (POST)
// ========================
$inputData = [];

if ($method === 'POST') {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);

    // JSON invalide
    if ($raw && !$decoded) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON'
        ]);
        exit;
    }

    $inputData = $decoded ?? $_POST;
}

// ========================
// GET : liste ou détail
// ========================
if ($method === 'GET') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if ($id) {
        $controller->show($id);
    } else {
        $controller->index();
    }

    exit;
}

// ========================
// POST : ajouter ou modifier
// ========================
if ($method === 'POST') {

    $id = !empty($inputData['id']) ? (int)$inputData['id'] : null;

    if ($id) {
        $controller->update($id, $inputData);
    } else {
        $controller->store($inputData);
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