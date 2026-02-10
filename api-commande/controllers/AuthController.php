<?php
require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController extends BaseModel {

    public function login($login, $key) {

        $stmt = $this->getAll(
            "utilisateur",
            "WHERE login = ? AND id_unique_etablissement = ?",
            [$login, hash('sha256', $key)]
        );

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            Response::error("Identifiants incorrects", 401);
        }

        $token = Auth::generateToken($user);

        Response::success([
            "token" => $token,
            "user" => $user['login']
        ]);
    }
}
