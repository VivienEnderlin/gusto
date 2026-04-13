<?php
require_once __DIR__ . '/BaseModel.php';

class QrCode extends BaseModel {

    private $secret = "CLE_SECRETE_GUSTO";

    public function generateQrUrl($id_etablissement, $id_table)
    {
        $signature = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $this->secret);

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "http://gusto/web/menu.php?code=" . $code;
    }
}