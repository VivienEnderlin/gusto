<?php
require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class MenuController {

    private $menu;

    public function __construct() {
        $this->menu = new Menu();
        Middleware::checkAuth();
    }

    public function index() {
        Response::success($this->menu->getAllMenu());
    }

    public function store($data) {
        $this->menu->create($data);
        Response::success(["message" => "Menu ajouté"]);
    }

    public function update($id, $data) {
        $this->menu->updateMenu($id, $data);
        Response::success(["message" => "Menu modifié"]);
    }

    public function delete($id) {
        $this->menu->deleteMenu($id);
        Response::success(["message" => "Menu supprimé"]);
    }

    // Récupérer une Menu par ID
    public function show($id) {
        $menu = $this->menu->getById($id);
        if ($menu) {
            Response::success($menu);
        } else {
            Response::error("Menu non trouvée", 404);
        }
    }
}
