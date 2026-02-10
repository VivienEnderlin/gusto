<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class UtilisateurController {

    private $model;

    public function __construct() {
        $this->model = new Utilisateur();
        Middleware::checkAuth(); // protège toutes les routes
    }

    public function index() {
        Response::success($this->model->getAll());
    }

    public function show($id) {
        $user = $this->model->getById($id);
        if ($user) Response::success($user);
        else Response::error("Utilisateur non trouvé", 404);
    }

    public function store($data) {
        $this->model->create($data);
        Response::success(["message" => "Utilisateur créé"]);
    }

    public function update($id, $data) {
        $this->model->update($id, $data);
        Response::success(["message" => "Utilisateur mis à jour"]);
    }

    public function delete($id) {
        $this->model->delete($id);
        Response::success(["message" => "Utilisateur supprimé"]);
    }
}
