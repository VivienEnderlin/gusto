<?php
require_once __DIR__ . '/../controllers/CommandeController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new CommandeController();
$method = $_SERVER['REQUEST_METHOD'];

// ========================
// Vérification du token
// ========================
$headers = function_exists('getallheaders') ? getallheaders() : [];
$token = $headers['Authorization'] ?? null;

// ========================
// Lire le body JSON
// ========================
$inputData = [];
if (in_array($method, ['POST', 'PATCH'])) {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);

    if ($raw && !$decoded) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'Invalid JSON']);
        exit;
    }

    $inputData = $decoded ?? $_POST;
}

// ========================
// GET : libre (sans token)
// ========================
if ($method === 'GET') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
    if ($id) {
        $controller->show($id);
        exit;
    }
    $controller->index();
    exit;
}

// ========================
// POST : créer OU modifier
// ========================
if ($method === 'POST') {
    $id = !empty($inputData['id']) ? (int)$inputData['id'] : null;

    // 🔥 CAS 1 : FILTRE AUTOMATIQUE (année → aujourd’hui)
    if (!empty($inputData['debut']) && !empty($inputData['fin'])) {

        $debut = $inputData['debut'];
        $fin   = $inputData['fin'];

        $controller->getByServiceRange($debut, $fin);
        exit;
    }

    // 👉 CAS 2 : CRÉATION
    if (!$id) {
        $controller->store($inputData);
        exit;
    }

    // 👉 CAS 3 : UPDATE
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'success'=>false,
            'message'=>'Token required'
        ]);
        exit;
    }

    $controller->update($id, $inputData);
    exit;
}

// ========================
// DELETE : token obligatoire
// ========================
if ($method === 'DELETE') {
    if (!$token) {
        http_response_code(401);
        echo json_encode(['success'=>false,'message'=>'Token required']);
        exit;
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID required']);
        exit;
    }

    $controller->delete($id);
    exit;
}

// ========================
// PATCH : changer statut (token obligatoire)
// ========================
if ($method === 'PATCH') {
    if (!$token) {
        http_response_code(401);
        echo json_encode(['success'=>false,'message'=>'Token required']);
        exit;
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID required']);
        exit;
    }

    $controller->changeStatus($id);
    exit;
}

// ========================
http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Unauthorised method']);
exit;
?>