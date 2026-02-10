<?php
require_once 'BaseModel.php';

class Menu extends BaseModel {

    public function getAllMenu() {
        return $this->getAll("menu")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->getAll("menu", "WHERE idmenu = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function create($data) {
        return $this->insert(
            "menu",
            ["libelle", "image", "idcategorie", "prix", "description"],
            [
                $data['libelle'],
                $data['image'],
                $data['idcategorie'],
                $data['prix'],
                $data['description']
            ]
        );
    }

    public function updateMenu($id, $data) {
        return $this->set(
            "menu",
            ["libelle", "image", "idcategorie", "prix", "description"],
            [
                $data['libelle'],
                $data['image'],
                $data['idcategorie'],
                $data['prix'],
                $data['description']
            ],
            "WHERE idmenu = ?",
            [$id]
        );
    }

    public function deleteMenu($id) {
        return $this->personalDelete(
            "menu",
            "WHERE idmenu = ?",
            [$id]
        );
    }
}
