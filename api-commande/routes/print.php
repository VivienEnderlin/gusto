<?php
require_once __DIR__ . '/../controllers/PrintController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new PrintController();
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
// POST → Impression
// ========================
if ($method === 'POST') {

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON'
        ]);
        exit;
    }

    // Vérification des champs
    if (!isset($data['id_table'], $data['commande'], $data['montant_total'], $data['devise'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Missing datas'
        ]);
        exit;
    }

    // Appel du controller avec paramètres
    $controller->imprimerFacture(
        (int) $data['id_table'],
        $data['commande'],
        $data['montant_total'],
        $data['devise']
    );

    exit;
}

// ========================
// Méthode non autorisée
// ========================
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Unauthorised method'
]);
exit;