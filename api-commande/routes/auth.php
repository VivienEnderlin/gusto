<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Auth.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$login = $data['login'] ?? '';
$password = $data['password'] ?? '';

if (!$login || !$password) {
    echo json_encode([
        'success' => false,
        'html' => '<div class="alert alert-block alert-danger">
                    <i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>
                    Champ manquant
                   </div>'
    ]);
    exit;
}

$userModel = new Utilisateur();
$user = $userModel->getByLogin($login);

if ($user && password_verify($password, $user['password'])) {

   
    $token = Auth::generateToken($user);
    echo json_encode([
        'success' => true,
        'token' => $token,
        'role'  => $user['role'],
    ]);
} else {
    echo json_encode([
        'success' => false,
    ]);
}
