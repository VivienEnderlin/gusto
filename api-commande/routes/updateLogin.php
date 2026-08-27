<?php
require_once __DIR__ . '/../core/Cors.php';
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

// 🔥 IMPORTANT : lire une seule fois
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON or empty body'
    ]);
    exit;
}

$login    = $data['login'] ?? null;
$password = $data['password'] ?? null;

if (!$login) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$model = new Utilisateur();

$userId = $user->id;

// update
$model->updateLogin($userId, $login, $user->id_etablissement, $password);

// nouveau token
$etablissement = $model->getEtablissementById($user->id_etablissement) ?? [];

$newToken = Auth::generateToken([
    'id_utilisateur' => $userId,
    'login' => $login,
    'id_etablissement' => $user->id_etablissement ?? null,
    'logo' => $etablissement['logo'] ?? '',
    'nom'  => $etablissement['nom'] ?? ''
]);

echo json_encode([
    'success' => true,
    'message' => 'Updated information',
    'token' => $newToken
]);
exit;