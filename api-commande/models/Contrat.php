<?php
require_once __DIR__ . '/BaseModel.php';

class Contrat extends BaseModel {

    private function generateLicenceCode($idEtablissement, $dateValidite) {
        // 1️⃣ Récupérer le nom de l'établissement
        $stmt = $this->personnalSelect(
            "etablissement",
            "nom",
            "WHERE id_etablissement = ?",
            [$idEtablissement]
        );
        $etablissement = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomEtablissement = $etablissement ? preg_replace('/\s+/', '', strtoupper($etablissement['nom'])) : "UNKNOWN";

        // 2️⃣ Formater les dates
        $dateCreation = date('Ymd');
        $dateValidite = date('Ymd', strtotime($dateValidite));

        // 3️⃣ Partie aléatoire
        $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

        // 4️⃣ Retourner le code licence
        return "{$nomEtablissement}-{$dateCreation}-{$dateValidite}-{$random}";
    }
    
    // Récupérer tous les établissements
    public function getAllContrats() {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE role != ?",
            [0]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilsateur par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_utilisateur = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un établissement
    public function update($id, $data) {

        $licence = $data['code'] ?? $this->generateLicenceCode($data['id_etablissement'], $data['date_validite']);

        return $this->set(
            "utilisateur",
            ["code", "date_validite", "statu"],
            [
                $licence,
                $data['date_validite'],
                "Valide"
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }
}
?>
