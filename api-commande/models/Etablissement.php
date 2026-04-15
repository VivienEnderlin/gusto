<?php
require_once __DIR__ . '/BaseModel.php';

class Etablissement extends BaseModel {

    // Récupérer tous les établissements
    public function getAllEtablissements() {
        $stmt = $this->getAll("etablissement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un établissement par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "etablissement",
            "*",
            "WHERE id_etablissement = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un nouvel établissement
    public function create($data) {

        $this->insert(
            "etablissement",
            ["logo", "nom", "type", "pays", "devise", "ville", "adresse", "email", "telephone", "country", "site_web", "description", "date_enreg"],
            [
                $data['logo'],
                $data['nom'],
                $data['type'],
                $data['pays'],
                $data['devise'],
                $data['ville'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['country'],
                $data['site_web'],
                $data['description'],
                date('Y-m-d'),
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour un établissement
    public function update($id, $data) {
        return $this->set(
            "etablissement",
            ["logo", "nom", "type", "pays", "devise", "ville", "adresse", "email", "telephone", "country", "site_web", "description"],
            [
                $data['logo'],
                $data['nom'],
                $data['type'],
                $data['pays'],
                $data['devise'],
                $data['ville'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['country'],
                $data['site_web'],
                $data['description']
            ],
            "WHERE id_etablissement = ?",
            [$id]
        );
    }

}
?>
