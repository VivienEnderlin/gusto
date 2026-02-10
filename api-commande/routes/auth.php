<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Auth.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$login = $data['login'] ?? '';
$id_unique_etablissement = $data['id_unique_etablissement'] ?? '';

if (!$login || !$id_unique_etablissement) {
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
$user = $userModel->login($login, hash('sha256', $id_unique_etablissement));

if ($user) {
    $token = Auth::generateToken($user);
    echo json_encode([
        'success' => true,
        'token' => $token,
        'html' => '<div class="alert alert-block alert-success">
                    <i class="icofont-check" style="margin-right: 10px; font-weight: bold;"></i>
                    Vous êtes connecté
                   </div>'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'html' => '<div class="alert alert-block alert-danger">
                    <i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>
                    Information incorrect
                   </div>'
    ]);
}
