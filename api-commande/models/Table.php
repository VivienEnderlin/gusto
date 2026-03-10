<?php
require_once __DIR__ . '/BaseModel.php';

class Table extends BaseModel {

    // Récupérer toutes les tables d'un restaurant
    public function getTablesByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_table = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID et restaurant (sécurisé)
    public function getByIdAndRestaurant($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_table = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer
    public function create($data) {
        $this->insert(
            "tables_restaurant",
            ["Nom", "id_etablissement"],
            [
                $data['nom'],
                $data['id_etablissement']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour une table
    public function update($id, $data) {
        return $this->set(
            "tables_restaurant",
            ["Nom", "id_etablissement"],
            [
                $data['nom'],
                $data['id_etablissement']
            ],
            "WHERE id_table = ?",
            [$id]
        );
    }

    // Supprimer une table en sécurisant par restaurant
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "tables_restaurant",
            "WHERE id_table = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

}
?>