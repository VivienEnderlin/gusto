<?php
require_once __DIR__ . '/BaseModel.php';

class Service extends BaseModel {

    // =========================
    // Tous les services d’un établissement
    // =========================
    public function getServicesByEtablissement($id_etablissement){
        $stmt = $this->personnalSelect(
            "service",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID
    // =========================
    public function getById($id){
        $stmt = $this->personnalSelect(
            "service",
            "*",
            "WHERE id_service = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Sécurisé par établissement
    // =========================
    public function getByIdAndRestaurant($id,$id_etablissement){
        $stmt = $this->personnalSelect(
            "service",
            "*",
            "WHERE id_service = ? AND id_etablissement = ?",
            [$id,$id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Création
    // =========================
    public function create($data){
        $this->insert(
            "service",
            [
                "id_table",
                "id_etablissement",
                "id_utilisateur",
                "date_heure_ouverture",
                "date_heure_fermeture",
                "statu"
            ],
            [
                $data['id_table'],
                $data['id_etablissement'],
                $data['id_utilisateur'],
                $data['date_heure_ouverture'],
                $data['date_heure_fermeture'],
                $data["statu"]
            ]
        );
        return $this->pdo->lastInsertId();
    }

    // =========================
    // Changer le statut
    // =========================
    public function toggleStatut($id) {
        $e = $this->getById($id);
        if (!$e) return false;

        if ($e['statu'] === 'Ouvert') {
            return $this->set(
                "service",
                ["statu", "date_heure_fermeture"],
                ["Fermer", date('Y-m-d H:i:s')],
                "WHERE id_service = ?",
                [$id]
            );
        } 
    }
}
?>