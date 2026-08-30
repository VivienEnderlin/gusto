<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/../core/Cors.php';
require_once __DIR__ . '/../controllers/EtablissementController.php';
require_once __DIR__ . '/../core/Middleware.php';

header('Content-Type: application/json; charset=utf-8');
$user = Middleware::checkAuth();

$controller = new EtablissementController();
$method = $_SERVER['REQUEST_METHOD'];


// ========================
// Lire le body JSON (POST)
// ========================
$inputData = [];

if ($method === 'POST') {

    // Si on reçoit un formulaire avec fichier
    if (!empty($_FILES)) {
        $inputData = $_POST;
    } 
    // Sinon, on reçoit du JSON
    else {
        $raw = file_get_contents('php://input');

        if (!empty($raw)) {
            $decoded = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON',
                    'error' => json_last_error_msg()
                ]);

                exit;
            }

            $inputData = $decoded;
        }
    }
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