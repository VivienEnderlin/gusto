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

    public function getByNomAndEtablissement($nom, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "produit",
            "*",
            "WHERE nom = ? AND id_etablissement = ?",
            [$nom, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $nom = trim($data['nom']);

        // Vérifier si le nom existe déjà dans cet établissement
        $existing = $this->getByNomAndEtablissement($nom, $id_etablissement);

        if ($existing) {
            return false;
        }
        $stmt = $this->personnalSelect(
            "etablissement",
            "devise",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );

        $devise = $stmt->fetch(PDO::FETCH_ASSOC)['devise'];
        $this->insert(
            "produit",
            ["id_etablissement", "nom", "image", "id_categorie", "prix", "devise", "description"],
            [
                $id_etablissement,
                $data['nom'],
                $data['image'] ?? null,
                $data['id_categorie'] ?? null,
                $data['prix'] ?? 0,
                $devise,
                $data['description'] ?? ''
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // =========================
    // Mettre à jour un produit
    // =========================
    public function update($id, $id_etablissement, $data) {
        $current = $this->getByIdAndEtablissement($id, $id_etablissement);
        return $this->set(
            "produit",
            ["nom", "image", "id_categorie", "prix", "description"],
            [
                $data['nom'],
                $data['image'] ,
                $data['id_categorie'],
                $data['prix'],
                $data['description']
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