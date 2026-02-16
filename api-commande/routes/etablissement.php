<?php
require_once __DIR__ . '/../controllers/EtablissementController.php';

$controller = new EtablissementController();
$method = $_SERVER['REQUEST_METHOD'];
$data = $_POST;

// Pour PUT multipart/form-data
if ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) $controller->show($_GET['id']);
        else $controller->index();
        break;

    case 'POST':
        $controller->store($data);
        break;

    case 'PUT':
        if (!isset($_GET['id'])) exit(json_encode(['success'=>false,'message'=>'ID requis']));
        $controller->update($_GET['id'], $data);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) exit(json_encode(['success'=>false,'message'=>'ID requis']));
        $controller->delete($_GET['id']);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Méthode non autorisée']);
}
