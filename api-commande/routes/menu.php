<?php
require_once __DIR__ . '/../controllers/MenuController.php';

$controller = new MenuController();
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $controller->index();
        break;

    case 'POST':
        $controller->store($data);
        break;

    case 'PUT':
        $controller->update($_GET['id'], $data);
        break;

    case 'DELETE':
        $controller->delete($_GET['id']);
        break;

    default:
        echo json_encode(["error" => "Méthode non autorisée"]);
}
