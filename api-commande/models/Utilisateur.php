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
                "id_unique_etablissement",
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
                "id_unique_etablissement",
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
    ======================= */

    public function login($login, $key) {
        $stmt = parent::getAll(
            "utilisateur",
            "WHERE login = ? AND id_unique_etablissement = ?",
            [$login, $key]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
