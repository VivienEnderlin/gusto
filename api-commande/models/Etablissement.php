<?php
require_once 'BaseModel.php';

class Etablissement extends BaseModel {

    public function getAllEtablissements() {
        return $this->getAll("etablissement")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->get(
            "etablissement",
            "WHERE id_etablissement = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        return $this->insert(
            "etablissement",
            ["logo", "nom", "type", "adresse", "email", "telephone", "site_web", "description", "dateenreg"],
            [
                $data['logo'],
                $data['nom'],
                $data['type'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['site_web'],
                $data['description'],
                date('Y-m-d')
            ]
        );
    }

    public function update($id, $data) {
        return $this->updateData(
            "etablissement",
            ["logo", "nom", "type", "adresse", "email", "telephone", "site_web", "description"],
            [
                $data['logo'],
                $data['nom'],
                $data['type'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['site_web'],
                $data['description']
            ],
            "WHERE id_etablissement = ?",
            [$id]
        );
    }

    public function delete($id) {
        return $this->personalDelete(
            "etablissement",
            "WHERE id_etablissement = ?",
            [$id]
        );
    }
}
