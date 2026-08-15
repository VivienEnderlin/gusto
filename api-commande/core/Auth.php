<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {

    public static function generateToken($user) {
        $config = require __DIR__ . '/../config/config.php';

        $payload = [
            "iss" => "api-commande",
            "iat" => time(),
            "exp" => time() + $config['JWT_EXPIRE'],
            "data" => [
                "id"    => $user['id_utilisateur'],
                "login" => $user['login'],
                "date" => $user['date_validite'],
                "id_etablissement" => $user['id_etablissement'],
                "logo" => $user['logo'] ?? '',
                "nom"  => $user['nom'] ?? ''
            ]
        ];

        return JWT::encode(
            $payload,
            $config['JWT_SECRET'],
            $config['JWT_ALGO']
        );
    }

    public static function verifyToken($token) {
        $config = require __DIR__ . '/../config/config.php';

        return JWT::decode(
            $token,
            new Key($config['JWT_SECRET'], $config['JWT_ALGO'])
        );
    }
}
