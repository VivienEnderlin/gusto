<?php
require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController extends BaseModel {
//On récupère juste l’utilisateur par son login, puis on utilise password_verify($key, $hash) pour comparer le mot de passe en PHP
    public function login($login, $key, $role) {

        $stmt = $this->getAll(
            "utilisateur",
            "WHERE login = ? AND role = ?",
            [$login,$role]
        );

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            Response::error("Identifiants incorrects", 401);
        }

         if (!password_verify($key, $user['password'])) {
            Response::error("Identifiants incorrects", 401);
        }

        $token = Auth::generateToken($user);

        Response::success([
            "token" => $token,
            "user" => $user['login']
        ]);
    }
}
