<?php
require_once __DIR__ . '/BaseModel.php';

class Categorie extends BaseModel {

    // Récupérer toutes les catégories d'un restaurant
    public function getCategoriesByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "categorie",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID et restaurant (sécurisé)
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "categorie",
            "*",
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer
    public function create($data) {
        $this->insert(
            "categorie",
            ["id_etablissement", "libelle"],
            [
                $data['id_etablissement'],
                $data['libelle']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour
    public function update($id, $data) {
        return $this->set(
            "categorie",
            ["id_etablissement", "libelle"],
            [
                $data['id_etablissement'],
                $data['libelle']
            ],
            "WHERE id_categorie = ?",
            [$id]
        );
    }

    // Supprimer
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "categorie",
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

}
?>