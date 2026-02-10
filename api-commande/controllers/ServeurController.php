<?php
require_once __DIR__ . '/../models/Serveur.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class ServeurController {
    private $model;

    public function __construct() {
        $this->model = new Serveur();
        Middleware::checkAuth();
    }

    public function index() { Response::success($this->model->getAll()); }
    public function show($id) { 
        $srv = $this->model->getById($id);
        $srv ? Response::success($srv) : Response::error("Serveur non trouvé", 404);
    }
    public function store($data) { $this->model->create($data); Response::success(["message"=>"Serveur créé"]); }
    public function update($id,$data) { $this->model->update($id,$data); Response::success(["message"=>"Serveur mis à jour"]); }
    public function delete($id) { $this->model->delete($id); Response::success(["message"=>"Serveur supprimé"]); }
}
