<?php
require_once __DIR__ . './../core/Middleware.php';
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
                    Missing field
                   </div>'
    ]);
    exit;
}


$userModel = new Utilisateur();

$userModel->checkAndExpireContrats();

$user = $userModel->getByLogin($login);

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode([
        'success' => false,
    ]);

} elseif ($user['statu'] == 'Expiré') {
    echo json_encode([
        'success' => false,
    ]);

} else {
    $token = Auth::generateToken($user);
    echo json_encode([
        'success' => true,
        'token' => $token,
        'role'  => $user['role'],
    ]);
}
