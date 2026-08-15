<?php
require_once __DIR__ . '/BaseModel.php';

class Categorie extends BaseModel {

    // =========================
    // Récupérer toutes les catégories d'un établissement
    // =========================
    public function getCategoriesByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "categorie",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByLibelleAndEtablissement($libelle, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE libelle = ? AND id_etablissement = ?",
            [$libelle, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "categorie",
            "*",
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Créer une catégorie
    // =========================
    public function create($data, $id_etablissement) {
        $libelle = trim($data['libelle']);

        // Vérifier si le libelle existe déjà dans cet établissement
        $existing = $this->getByLibelleAndEtablissement($libelle, $id_etablissement);

        if ($existing) {
            return false;
        }
        $this->insert(
            "categorie",
            ["id_etablissement", "libelle"],
            [
                $id_etablissement,
                $data['libelle']
            ]
        );
        return $this->pdo->lastInsertId();
    }

    // =========================
    // Mettre à jour une catégorie
    // =========================
    public function update($id, $id_etablissement, $data) {
        return $this->set(
            "categorie",
            ["libelle"],
            [
              $data['libelle']
            ],
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    // =========================
    // Supprimer une catégorie (sécurisé par établissement)
    // =========================
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "categorie",
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }
}
?>