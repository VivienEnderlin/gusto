<?php
require_once __DIR__ . './../models/Utilisateur.php';
require_once __DIR__ . './../core/Auth.php';
require_once __DIR__ . './../core/Middleware.php';

header('Content-Type: application/json');

$user = Middleware::checkAuth();

if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not permitted']);
    exit;
}

$login    = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

if (!$login) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$model = new Utilisateur();

$userId = $user->id;

// Mise à jour
$model->updateLogin($userId, $login, $user->id_etablissement, $password);

// ✅ correction token
$newToken = Auth::generateToken([
    'id_utilisateur' => $userId,
    'login' => $login,
    'id_etablissement' => $user->id_etablissement ?? null
]);

echo json_encode([
    'success' => true,
    'message' => 'Updated information',
    'token'   => $newToken
]);
exit;