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
        echo json_encode(['success'=>false,'message'=>'JSON invalide']);
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
    } else {
        $controller->index();
    }
    exit;
}

// ========================
// POST : créer OU modifier
// ========================
if ($method === 'POST') {
    $id = !empty($inputData['id']) ? (int)$inputData['id'] : null;

    // 👉 CAS 1 : CRÉATION (SANS TOKEN)
    if (!$id) {
        $controller->store($inputData);
        exit;
    }

    // 👉 CAS 2 : UPDATE (TOKEN OBLIGATOIRE)
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'success'=>false,
            'message'=>'Token requis pour modifier'
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
        echo json_encode(['success'=>false,'message'=>'Token requis']);
        exit;
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID requis']);
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
        echo json_encode(['success'=>false,'message'=>'Token requis']);
        exit;
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID requis']);
        exit;
    }

    $controller->changeStatus($id);
    exit;
}

// ========================
http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
exit;
?>