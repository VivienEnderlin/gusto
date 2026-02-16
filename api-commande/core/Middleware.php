<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Middleware {

    public static function checkAuth() {
        // Récupérer config
        $config = require __DIR__ . '/../config/config.php';
        $secret = $config['JWT_SECRET'];
        $algo   = $config['JWT_ALGO'];

        // Récupérer le header Authorization
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            Response::error("Token requis", 401);
            exit;
        }

        // Extraire le token
        $authHeader = $headers['Authorization'];
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::error("Format du token invalide", 401);
            exit;
        }

        $token = $matches[1];

        // Décoder le token
        try {
            $decoded = JWT::decode($token, new Key($secret, $algo));
            return $decoded;
        } catch (Exception $e) {
            Response::error("Token invalide: " . $e->getMessage(), 401);
            exit;
        }
    }
}
