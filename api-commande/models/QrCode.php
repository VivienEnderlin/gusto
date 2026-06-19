<?php
require __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}
require_once __DIR__ . '/BaseModel.php';

class QrCode extends BaseModel {

    private $secret;

    public function __construct()
    {
        $this->secret = getenv('secret_key');
        //ce qui marche en local
        //$this->secret = $_ENV['secret_key'];
    }

    public function generateQrUrl($id_etablissement, $id_table)
    {
        $signature = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $this->secret);

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "https://gusto-api-48f214a89058.herokuapp.com/web/check.php?code=" . $code;
    }
}