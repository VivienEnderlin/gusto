<?php
require_once __DIR__ . '/../controllers/Commande_ClientController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new Commande_ClientController();
$method = $_SERVER['REQUEST_METHOD'];

// ========================
// Lire le body JSON (POST)
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
        // UPDATE → token obligatoire
        if (!$token) {
            http_response_code(401);
            echo json_encode(['success'=>false,'message'=>'Token requis pour modifier']);
            exit;
        }
        $controller->update($id, $inputData);
    } else {
        // STORE → client autorisé
        $controller->store($inputData);
    }
    exit;
}

// ========================
// DELETE : supprimer
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
// PATCH : changer statut
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
// Méthodes non autorisées
// ========================
http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
exit;
?>