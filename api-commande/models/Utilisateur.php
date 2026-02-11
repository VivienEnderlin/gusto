<?php
require_once 'BaseModel.php';

class Utilisateur extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    public function getAllUsers() {
        $stmt = parent::getAll("utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = parent::getAll(
            "utilisateur",
            "WHERE login = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data) {
        return $this->insert(
            "utilisateur",
            [
                "id_utilisateur",
                "nom",
                "prenom",
                "adesse",
                "email",
                "telephone",
                "login",
                "password",
                "id_etablissement",
                "role",
                "date_enreg"
            ],
            [
                $data['id_utilisateur'],
                $data['nom'],
                $data['prenom'],
                $data['adesse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                $data['id_unique_etablissement'],
                $data['role'],
                date('Y-m-d')
            ]
        );
    }

    public function update($id, $data) {
        return $this->set(
            "utilisateur",
            [
                "id_utilisateur",
                "nom",
                "prenom",
                "adesse",
                "email",
                "telephone",
                "login",
                "password",
                "id_etablissement",
                "role",
                "date_enreg"
            ],
            [
                $data['id_utilisateur'],
                $data['nom'],
                $data['prenom'],
                $data['adesse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                $data['id_etablissement'],
                $data['role'],
                date('Y-m-d')
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    public function delete($id) {
        return $this->personalDelete(
            "utilisateur",
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    /* =======================
       AUTHENTIFICATION
       On ne prend que le login et pas le mot de passe. La raison est simple :

1️⃣ Le mot de passe est hashé dans la base
Quand tu fais password_hash("admin", PASSWORD_DEFAULT), le mot de passe stocké n’est pas “admin”, mais une chaîne cryptée complexe
    ======================= */

    public function login($login) {
        $stmt = parent::getAll(
            "utilisateur",
            "WHERE login = ?",
            [$login]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
