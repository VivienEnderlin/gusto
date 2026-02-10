<?php
require_once __DIR__ . '/../controllers/UtilisateurController.php';
$controller = new UtilisateurController();
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) $controller->show($_GET['id']);
        else $controller->index();
        break;
    case 'POST':
        $controller->store($data);
        break;
    case 'PUT':
        if (!isset($_GET['id'])) Response::error("ID requis");
        $controller->update($_GET['id'], $data);
        break;
    case 'DELETE':
        if (!isset($_GET['id'])) Response::error("ID requis");
        $controller->delete($_GET['id']);
        break;
    default:
        Response::error("Méthode non autorisée", 405);
}
