<?php
require_once __DIR__ . '/BaseModel.php';

class Commande extends BaseModel {

    // Récupérer toutes les tables d'un restaurant
    public function getCommandesByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_commande = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer par ID et restaurant (sécurisé)
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "commande",
            "*",
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isTableActive($id_table, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "service",
            "*",
            "WHERE id_table = ? AND id_etablissement = ? AND date_heure_fermeture IS NULL",
            [$id_table, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer
    public function create($data, $id_etablissement) {
        $this->insert(
            "commande",
            ['id_table','id_etablissement','commande','montant_total','etat'],
            [
                $data['id_table'],
                $id_etablissement,
                json_encode($data['commande']),
                $data['montant_total'],
                "En attente"
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour une table
    public function update($id, $id_etablissement, $data) {
        return $this->set(
            "commande",
            ['id_table','commande','montant_total','etat'],
            [
                $data['id_table'],
                json_encode($data['commande']),
                $data['montant_total'],
                $data['etat'],
            ],
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    // Supprimer une table en sécurisant par restaurant
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "commande",
            "WHERE id_commande = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    public function toggleStatut($id, $id_etablissement) {
        // Récupérer la commande sécurisée
        $e = $this->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) return false;

        // Définir la progression des statuts
        $next = [
            'En attente' => 'Servi',
            'Servi' => 'Payé'
        ];

        // Vérifier s'il y a un statut suivant
        if (isset($next[$e['etat']])) {
            $this->set(
                "commande",
                ["etat"],
                [$next[$e['etat']]],
                "WHERE id_commande = ? AND id_etablissement = ?",
                [$id, $id_etablissement]
            );
        }

        return true;
    }

}
?>