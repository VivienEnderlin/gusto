<?php
require_once __DIR__ . '/../controllers/ServeurController.php';
$controller = new ServeurController();
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        isset($_GET['id']) ? $controller->show($_GET['id']) : $controller->index();
        break;
    case 'POST': $controller->store($data); break;
    case 'PUT': 
        isset($_GET['id']) ? $controller->update($_GET['id'],$data) : Response::error("ID requis");
        break;
    case 'DELETE':
        isset($_GET['id']) ? $controller->delete($_GET['id']) : Response::error("ID requis");
        break;
    default: Response::error("Méthode non autorisée",405);
}
