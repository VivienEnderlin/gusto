<?php
require_once __DIR__ . '/../controllers/TableController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new TableController();
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
// Lire le body JSON (POST, PATCH, DELETE)
// ========================
$inputData = [];

if (in_array($method, ['POST', 'PATCH', 'DELETE'])) {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);

    // Vérifier JSON invalide
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
// GET : Liste ou détail
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
// POST : Ajouter ou Modifier
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
// DELETE : Supprimer
// ========================
if ($method === 'DELETE') {

    // Support JSON + query param
    $id = $inputData['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID required'
        ]);
        exit;
    }

    $controller->delete((int)$id);
    exit;
}

// ========================
// PATCH : changer statut
// ========================
if ($method === 'PATCH') {

    $id = $inputData['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID required'
        ]);
        exit;
    }
    // 🔥 on passe maintenant les données
    $controller->changeStatus((int)$id, $inputData);
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