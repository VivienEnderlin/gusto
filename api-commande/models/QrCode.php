<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class QrCode extends BaseModel {

    private $secret;

    public function __construct() {

        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        $this->secret = getenv('secret_key');
    }

    // ======================
    // IMPORTANT: FIX MANQUANT
    // ======================
    public function getByIdAndEtablissement($id, $id_etablissement)
    {

        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_table = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================
    // QR URL SECURE
    // ======================
    public function generateQrUrl($id_etablissement, $id_table)
    {
        if (!$this->secret) return null;

        $signature = hash_hmac(
            'sha256',
            $id_etablissement . ":" . $id_table,
            $this->secret
        );

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "https://gusto-api-48f214a89058.herokuapp.com/web/check.php?code=" . $code;
    }
}