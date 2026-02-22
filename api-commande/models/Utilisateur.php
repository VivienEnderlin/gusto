<?php
require_once 'BaseModel.php';

class Utilisateur extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    function generateRestaurantCode(string $name, int $randomLength = 8): string
    {
        // Nettoyer le nom
        $prefix = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $name));
        $prefix = substr($prefix, 0, 10);

        // Partie aléatoire sécurisée
        $random = strtoupper(bin2hex(random_bytes(ceil($randomLength / 2))));
        $random = substr($random, 0, $randomLength);

        return $prefix . '-' . $random;
    }

    public function getAllUsers() {
        $stmt = $this->getAll("utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_utilisateur = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data) {

        $data['statu'] = $data['statu'] ?? 'Activer';

        // Génération automatique du mot de passe
        $data['password'] = $data['password'] ?? $this->generateRestaurantCode($data['nom']);

        $this->insert(
            "utilisateur",
            [
                "nom",
                "adresse",
                "email",
                "telephone",
                "login",
                "password",
                "id_etablissement",
                "role",
                "date_enreg",
                "statu"
            ],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['id_etablissement'],
                $data['role'],
                date('Y-m-d'),
                $data['statu']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        return $this->set(
            "utilisateur",
            [
                "nom",
                "adresse",
                "email",
                "telephone",
                "login",
                "id_etablissement",
                "role"
            ],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                $data['id_etablissement'],
                $data['role']
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    //change statut

    public function toggleStatut($id) {
        $e = $this->getById($id);
        if (!$e) return false;

        $newStatu = ($e['statu'] === 'Activer') ? 'Bloquer' : 'Activer';

        return $this->set(
            "utilisateur",
            ["statu"],
            [$newStatu],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    /* =======================
       AUTH
    ======================= */

    public function getByLogin($login) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE login = ?",
            [$login]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
