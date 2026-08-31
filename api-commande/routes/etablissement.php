<?php
require_once __DIR__ . '/../core/Cors.php';
require_once __DIR__ . '/../controllers/EtablissementController.php';
require_once __DIR__ . '/../core/Middleware.php';

header('Content-Type: application/json; charset=utf-8');
$user = Middleware::checkAuth();

$controller = new EtablissementController();
$method = $_SERVER['REQUEST_METHOD'];

var_dump("ETAPE 1 : route atteinte");
var_dump("METHOD :", $method);
;


// ========================
// Lire le body JSON (POST)
// ========================
$inputData = [];

if ($method === 'POST') {

    var_dump("ETAPE 2 : POST détecté");
var_dump("RAW :", file_get_contents('php://input'));
var_dump("POST :", $_POST);
var_dump("FILES :", $_FILES);

    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);

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