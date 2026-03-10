<?php
require_once __DIR__ . '/BaseModel.php';

class Produit extends BaseModel {

    // =========================
    // Récupérer tous les produits d'un restaurant
    // =========================
    public function getProduitsByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "produit",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID
    // =========================
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "produit",
            "*",
            "WHERE id_produit = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et restaurant (sécurisé)
    // =========================
    public function getByIdAndRestaurant($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "produit",
            "*",
            "WHERE id_produit = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Créer un produit
    // =========================
    public function create($data) {
        $this->insert(
            "produit",
            ["id_etablissement", "Nom", "image", "id_categorie", "prix", "description", "statu"],
            [
                $data['id_etablissement'],
                $data['Nom'],
                $data['image'],
                $data['id_categorie'],
                $data['prix'],
                $data['description'],
                $data['statu']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // =========================
    // Mettre à jour un produit
    // =========================
    public function update($id, $data) {
        return $this->set(
            "produit",
            ["id_etablissement", "Nom", "image", "id_categorie", "prix", "description", "statu"],
            [
                $data['id_etablissement'],
                $data['Nom'],
                $data['image'],
                $data['id_categorie'],
                $data['prix'],
                $data['description'],
                $data['statu']
            ],
            "WHERE id_produit = ?",
            [$id]
        );
    }

    // =========================
    // Supprimer un produit (sécurisé par restaurant)
    // =========================
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "produit",
            "WHERE id_produit = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

}
?>