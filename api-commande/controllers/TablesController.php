<?php
require_once __DIR__ . '/../models/Table.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class TableController {
    private $model;

    public function __construct() {
        $this->model = new Table();
        Middleware::checkAuth();
    }

    public function index() { Response::success($this->model->getAll()); }
    public function show($id) { 
        $table = $this->model->getById($id);
        $table ? Response::success($table) : Response::error("Table non trouvée", 404);
    }
    public function store($data) { $this->model->create($data['idtable']); Response::success(["message"=>"Table créée"]); }
    public function update($id,$data) { $this->model->update($id,$data['idtable']); Response::success(["message"=>"Table mise à jour"]); }
    public function delete($id) { $this->model->delete($id); Response::success(["message"=>"Table supprimée"]); }
}
