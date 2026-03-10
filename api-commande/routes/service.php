<?php
require_once __DIR__ . '/../controllers/ServiceController.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new ServiceController();
$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();

if (!isset($headers['Authorization'])) {

    echo json_encode([
        'success'=>false,
        'message'=>'Token requis'
    ]);

    exit;
}

// GET
if ($method === 'GET') {

    if (isset($_GET['id'])) {
        $controller->show($_GET['id']);
    } else {
        $controller->index();
    }

    exit;
}

// POST
if ($method === 'POST') {

    $data = $_POST;

    if (!empty($data['id'])) {
        $controller->update($data['id'],$data);
    } else {
        $controller->store($data);
    }

    exit;
}

if ($method === 'PATCH' && isset($_GET['id'])) {
    $controller->changeStatus($_GET['id']);
    exit;
}



echo json_encode([
    'success'=>false,
    'message'=>'Méthode non autorisée'
]);