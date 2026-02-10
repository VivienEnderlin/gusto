<?php
require_once __DIR__ . '/../controllers/CommandeController.php';

$controller = new CommandeController();
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $controller->show($_GET['id']);
        } else {
            $controller->index();
        }
        break;

    case 'POST':
        $controller->store($data);
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID requis"]);
            exit;
        }
        $controller->updateStatus($_GET['id'], $data);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID requis"]);
            exit;
        }
        $controller->delete($_GET['id']);
        break;

    default:
        echo json_encode(["error" => "Méthode non autorisée"]);
}
