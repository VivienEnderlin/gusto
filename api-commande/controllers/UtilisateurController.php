<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class UtilisateurController {

    private $model;
    private $user; // utilisateur connecté

    public function __construct() {
        // 🔐 Vérifie le token
        $this->user = Middleware::checkAuth();

        // ⛔ BLOQUER si pas admin
        if ((int)$this->user['role'] !== 0) {
            Response::error("Accès refusé : droits insuffisants", 403);
        }

        $this->model = new Utilisateur();
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        Response::success($this->model->getAllUsers());
    }

    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        $user = $this->model->getById($id);
        if ($user) {
            Response::success($user);
        } else {
            Response::error("Utilisateur non trouvé", 404);
        }
    }

    // =========================
    // CREER
    // =========================
    public function store($data) {
        $this->model->create($data);
        Response::success(["message" => "Utilisateur créé"]);
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        $this->model->update($id, $data);
        Response::success(["message" => "Utilisateur mis à jour"]);
    }

    // =========================
    // SUPPRIMER
    // =========================
    public function delete($id) {
        $this->model->delete($id);
        Response::success(["message" => "Utilisateur supprimé"]);
    }
}
