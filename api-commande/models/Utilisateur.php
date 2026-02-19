<?php
require_once 'BaseModel.php';

class Utilisateur extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

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
        return $this->insert(
            "utilisateur",
            [
                "nom",
                "prenom",
                "adresse",
                "email",
                "telephone",
                "login",
                "password",
                "id_etablissement",
                "role",
                "date_enreg"
            ],
            [
                $data['nom'],
                $data['prenom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['id_etablissement'],
                $data['role'],
                date('Y-m-d')
            ]
        );
    }

    public function update($id, $data) {
        return $this->set(
            "utilisateur",
            [
                "nom",
                "prenom",
                "adresse",
                "email",
                "telephone",
                "login",
                "id_etablissement",
                "role"
            ],
            [
                $data['nom'],
                $data['prenom'],
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

    public function delete($id) {
        return $this->personalDelete(
            "utilisateur",
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
