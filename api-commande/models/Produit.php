<?php
require_once __DIR__ . '/BaseModel.php';

class Produit extends BaseModel {

    // =========================
    // Récupérer tous les produits d'un établissement
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
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getByIdAndEtablissement($id, $id_etablissement) {
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
    public function create($data, $id_etablissement) {
        $this->insert(
            "produit",
            ["id_etablissement", "nom", "image", "id_categorie", "prix", "devise", "description"],
            [
                $data['id_etablissement'],
                $data['nom'],
                $data['image'] ?? null,
                $data['id_categorie'] ?? null,
                $data['prix'] ?? 0,
                $data['devise'] ?? null,
                $data['description'] ?? ''
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // =========================
    // Mettre à jour un produit
    // =========================
    public function update($id, $id_etablissement, $data) {
        return $this->set(
            "produit",
            ["nom", "image", "id_categorie", "prix", "description"],
            [
                $data['nom'],
                $data['image'] ?? null,
                $data['id_categorie'] ?? null,
                $data['prix'] ?? 0,
                $data['description'] ?? ''
            ],
            "WHERE id_produit = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    // =========================
    // Supprimer un produit (sécurisé par établissement)
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