<?php
require_once __DIR__ . '/BaseModel.php';
require __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

class QrCodeModel extends BaseModel {

    private $secret;

    public function __construct()
    {
        parent::__construct(); // IMPORTANT
        $this->secret = getenv('secret_key');
        //ce qui marche en local
        //$this->secret = $_ENV['secret_key'];
    }

    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_table = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateQrUrl($id_etablissement, $id_table)
    {
        $signature = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $this->secret);

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "https://gusto-public-092192522763.herokuapp.com/assets/check.php?code=" . $code;
    }
}