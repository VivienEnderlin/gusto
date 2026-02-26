<?php
require_once __DIR__ . '/BaseModel.php';

class Appareil extends BaseModel {

    // Récupérer tous les établissements
    public function getAllAppareils() {
        $stmt = $this->getAll("appareil");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un appareil par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "appareil",
            "*",
            "WHERE id_appareil = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un nouvel appareil
    public function create($data) {
        $this->insert(
            "appareil",
            ["id_etablissement", "marque", "model", "numero_serie", "systeme_exploitation", "annee_fabrication", "date_fin_support", "description"],
            [
                $data['id_etablissement'],
                $data['marque'],
                $data['model'],
                $data['numero_serie'],
                $data['systeme_exploitation'],
                $data['annee_fabrication'],
                $data['date_fin_support'],
                $data['description']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    // Mettre à jour un établissement
    public function update($id, $data) {
        return $this->set(
            "appareil",
            ["id_etablissement", "marque", "model", "numero_serie", "systeme_exploitation", "annee_fabrication", "date_fin_support", "description"],
            [
                $data['id_etablissement'],
                $data['marque'],
                $data['model'],
                $data['numero_serie'],
                $data['systeme_exploitation'],
                $data['annee_fabrication'],
                $data['date_fin_support'],
                $data['description']
            ],
            "WHERE id_appareil = ?",
            [$id]
        );
    }

    public function delete($id){
        return $this->personalDelete(
            "appareil",
            "WHERE id_appareil = ?",
            [$id]
        );
    }

}
?>
