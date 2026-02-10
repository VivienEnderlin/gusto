<?php
require_once 'BaseModel.php';

class Commande extends BaseModel {

    public function getAllCommandes() {
        return $this->getAll("commande")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->getAll("commande", "WHERE idcommande = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        return $this->insert(
            "commande",
            ["idtable", "commande", "montantàpayer", "datedujour", "situation", "statu"],
            [
                $data['idtable'],
                json_encode($data['commande']), // on stocke la commande en JSON
                $data['montantàpayer'],
                date('Y-m-d H:i:s'),
                $data['situation'] ?? 'Nouvelle',
                $data['statu'] ?? 'En cours'
            ]
        );
    }

    public function updateStatus($id, $statu, $situation) {
        return $this->set(
            "commande",
            ["statu", "situation"],
            [$statu, $situation],
            "WHERE idcommande = ?",
            [$id]
        );
    }

    public function deleteCommande($id) {
        return $this->personalDelete(
            "commande",
            "WHERE idcommande = ?",
            [$id]
        );
    }
}
