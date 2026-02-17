<?php
require_once 'BaseModel.php';

class Etablissement extends BaseModel {

    // Récupérer tous les établissements
    public function getAllEtablissements() {
        // getAll() existe dans bdFonctions
        $stmt = $this->getAll("etablissement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un établissement par ID
    public function getById($id) {
        // get() n'existe plus → utiliser personnalSelect()
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

        // Retourne le dernier ID inséré
        return $this->pdo->lastInsertId();
    }


    // Mettre à jour un établissement
    public function update($id, $data) {
        return $this->set(
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

    // Supprimer un établissement
    public function delete($id) {
        return $this->personalDelete(
            "etablissement",
            "WHERE id_etablissement = ?",
            [$id]
        );
    }
}
?>
