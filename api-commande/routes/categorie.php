<?php
require_once __DIR__ . '/../controllers/CategorieController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new CategorieController();
$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();

// Vérification du token
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Token requis']);
    exit;
}

// GET : lister ou récupérer un 
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $controller->show($_GET['id']); //S’il y a un ID → afficher un 
    } else {
        $controller->index(); //Sinon → afficher tous les 
    }
    exit;
}

// POST : ajouter ou modifier
if ($method === 'POST') {
    $data = $_POST;
    if (!empty($data['id'])) {
        $controller->update($data['id'], $data);
    } else {
        $controller->store($data);
    }
    exit;
}

// DELETE : supprimer un 
if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID requis'
        ]);
        exit;
    }

    $controller->delete($_GET['id']);
    exit;
}


// Méthodes non autorisées
http_response_code(405);
echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
exit;
?>
