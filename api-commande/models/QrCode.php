<?php
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();
require_once __DIR__ . '/BaseModel.php';

class QrCode extends BaseModel {

    private $secret;

    public function __construct()
    {
        $this->secret = $_ENV['SECRET_KEY'];
    }

    public function generateQrUrl($id_etablissement, $id_table)
    {
        $signature = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $this->secret);

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "http://gusto/web/index.php?code=" . $code;
    }
}