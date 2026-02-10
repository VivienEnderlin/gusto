<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/Categorie.php';

// Vérifier le token JWT
$auth = new Auth();
if (!$auth->checkToken()) {
    Response::json(['error' => 'Token invalide'], 401);
    exit;
}

// Récupérer les catégories
$categorieModel = new Categorie();
$categories = $categorieModel->getAll(); // renvoie un tableau associatif

Response::json([
    'status' => 'success',
    'data' => $categories
]);
