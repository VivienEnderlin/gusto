<?php
require_once 'BaseModel.php';

class Serveur extends BaseModel {

    public function getAll() {
        return $this->getAll("serveur")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->getAll("serveur", "WHERE idserveur = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        return $this->insert(
            "serveur",
            ["nom","login","idunique","idtables","role"],
            [
                $data['nom'],
                $data['login'],
                $data['idunique'],
                $data['idtables'] ?? null,
                $data['role']
            ]
        );
    }

    public function update($id, $data) {
        return $this->set(
            "serveur",
            ["nom","login","idunique","idtables","role"],
            [
                $data['nom'],
                $data['login'],
                $data['idunique'],
                $data['idtables'] ?? null,
                $data['role']
            ],
            "WHERE idserveur = ?",
            [$id]
        );
    }

    public function delete($id) {
        return $this->personalDelete("serveur", "WHERE idserveur = ?", [$id]);
    }
}
